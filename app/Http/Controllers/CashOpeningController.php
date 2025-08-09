<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
use App\Models\CashBalance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CashOpeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */

       public function index(Request $request)
        {
            $search = $request->get('searchText');
            $openings = DB::table("cash_opening as co")
                ->join("users as us","us.id","=","co.users_id")
                ->select("co.id","co.opened_at","co.start_amount","co.cashbox_name","co.end_amount","co.closed_at","co.location","co.observations","co.status","co.end_amount","co.closed_at","us.name")
                ->orderBy('opened_at', 'desc')
                ->paginate(5);

            return view('sale.cash.index', compact('openings', 'search'));
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           $cash_opened = DB::table("cash_opening as co")
                ->where("co.users_id","=",auth()->user()->id)
                ->where("co.status","=",'open')
                ->first();
                    if(!$cash_opened){

                        $cashes = DB::table('cash as c')
                                  ->select("c.id","c.name")
                                  ->get();

                        $cash_registers = DB::table('config')
                          ->where('key','=','cash_registers')
                          ->first();
                        $cash_locations = DB::table('config')
                          ->where('key','=','cash_locations')
                          ->first();
                        return view('sale.cash.create',["cash_registers"=>explode(",",$cash_registers->value),"cash_locations"=>explode(",",$cash_locations->value),"cashes"=>$cashes]);
                    }
                    else
                    {
                        return back()->withErrors(["Ya existe una apertura de caja en estado abierta"])->withInput();
                    }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'start_amount' => 'required|numeric|min:0',
                'cashbox_name' => 'required|string|max:100',
                'location' => 'nullable|string|max:100',
                'cash_id' => 'required|numeric',
                'observations' => 'nullable|string',
            ]);
            
            $last_balances = DB::table("cash_balance")
                             ->get();

            $cash_opening = new CashOpening();
            $cash_opening->users_id = auth()->user()->id;
            $cash_opening->start_amount = $request->start_amount;
            $cash_opening->end_amount = 0;
            $cash_opening->opened_at = date("Y-m-d H:s:i");
            $cash_opening->cashbox_name = $request->cashbox_name;
            $cash_opening->location = $request->location;
            $cash_opening->observations = $request->observations;
            $cash_opening->cash_id = $request->cash_id;
            $cash_opening->status = 'open';
            $cash_opening->last_balances = json_encode($last_balances);
            $cash_opening->end_balances = json_encode([]);
            $cash_opening->save();

            return redirect()->route('sale.create');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            Log::error('Error al abrir caja: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error al abrir la caja.'])->withInput();
        }
    }

    public function validate_cash_opening(Request $request, $id){
        try{
                $cash_opened = DB::table("cash_opening as co")
                       ->where("co.users_id","=",auth()->user()->id)
                       ->where("co.status","=",'open')
                       ->first();
                if($cash_opened){
                    return response()->json(([
                        "success"=>true,
                        "message"=>$cash_opened
                    ]),200);
                }else{
                    return response()->json(([
                        "success"=>false,
                        "message"=>null
                    ]),200);
                }
        }catch(Exception $error){
            return response()->json(([
                "success"=>false,
                "message"=>$error
            ]),200);
        }   

    } 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Consulta paginada
        $cash_opened = DB::table('cash_opening as co')
              ->join('cash as c','c.id','=','co.cash_id')
              ->join('users as us','us.id','=','co.users_id')
              ->where("co.id","=",$id)
              ->select("us.name as username","c.name as cash_name","co.id","co.users_id","co.start_amount","co.opened_at","co.observations","co.status","co.created_at","co.cash_id","co.last_balances","co.end_balances","co.summary")
             ->orderBy('created_at', 'desc')
             ->first();


        $cash_opening_id =   $id;
            $sql = "
            SELECT * FROM (

                SELECT 
                    m.type COLLATE utf8mb4_unicode_ci AS type,
                    m.created_at,
                    mt.name COLLATE utf8mb4_unicode_ci AS movement_type,
                    m.description COLLATE utf8mb4_unicode_ci AS description,
                    m.amount,
                    m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                    us.name COLLATE utf8mb4_unicode_ci AS username
                FROM movement m
                JOIN movement_types mt ON mt.id = m.movement_type_id
                JOIN users us ON us.id = m.users_id
                WHERE  m.cash_opening_id = ? 
                
                UNION ALL
                
                SELECT 
                    'ingreso' COLLATE utf8mb4_unicode_ci AS type,
                    '' COLLATE utf8mb4_unicode_ci AS created_at,
                    'Venta' COLLATE utf8mb4_unicode_ci AS movement_type,
                    'Venta' COLLATE utf8mb4_unicode_ci AS description,
                    SUM(p.value) AS amount,
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    u.name COLLATE utf8mb4_unicode_ci AS username
                FROM sale s
                JOIN users u ON u.id = s.users_id
                JOIN payment p ON p.sale_id = s.id
                WHERE  s.cash_opening_id = ? 
                GROUP BY  p.method, u.name
            ) AS union_result
            ORDER BY created_at DESC
        ";

        // Parámetros del query
        $params = [
            $cash_opening_id,  
            $cash_opening_id      
        ];
        // Ejecutar consulta paginada
        $results = collect(DB::select($sql, $params));
            $sumSql = "
            SELECT payment_method, SUM(amount) AS total
            FROM (
                -- Movimientos
                SELECT 
                    m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                    m.amount
                FROM movement m
                WHERE  m.cash_opening_id = ?

                UNION ALL

                -- Ventas
                SELECT 
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    p.value AS amount
                FROM sale s
                JOIN payment p ON p.sale_id = s.id
                WHERE s.cash_opening_id = ?
            ) AS all_movements
            GROUP BY payment_method
        ";
        $sums = DB::select($sumSql, [
            $cash_opening_id,  
            $cash_opening_id   
        ]);

        $payment_methods = DB::table("config")
                    ->where('key',"=","payment_methods")
                    ->first();

        $last_cash_balances = json_decode($cash_opened->last_balances);
        $end_cash_balances = json_decode($cash_opened->end_balances); 
        
        $cash_count = $cash_opened->summary?json_decode($cash_opened->summary):json_decode("{}");

        return view('sale.cash.show',
        [
        'movements'=>$results,
        'totals'=>$sums,
        'payment_methods'=>explode(",",$payment_methods->value),
        'cash_opening'=>$cash_opened,
        'last_balances'=>$last_cash_balances,
        'current_balances'=>$end_cash_balances,
        'cash_count'=>$cash_count
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function summary_cash_close(){
        // Consulta paginada
        $users_id = auth()->user()->id;
        $cash_opened = DB::table('cash_opening as co')
              ->join('cash as c','c.id','=','co.cash_id')
              ->where("co.users_id","=",auth()->user()->id)
              ->select("c.name as cash_name","co.id","co.users_id","co.start_amount","co.opened_at","co.observations","co.status","co.created_at","co.cash_id","co.last_balances")
             ->orderBy('created_at', 'desc')
             ->first();
        $last_balances = json_decode($cash_opened->last_balances);

            $sql = "
            SELECT * FROM (

                SELECT 
                    m.type COLLATE utf8mb4_unicode_ci AS type,
                    m.created_at,
                    mt.name COLLATE utf8mb4_unicode_ci AS movement_type,
                    m.description COLLATE utf8mb4_unicode_ci AS description,
                    m.amount,
                    m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                    us.name COLLATE utf8mb4_unicode_ci AS username
                FROM movement m
                JOIN movement_types mt ON mt.id = m.movement_type_id
                JOIN users us ON us.id = m.users_id
                WHERE us.id = ? AND m.cash_id = ? 
                
                UNION ALL
                
                SELECT 
                    'ingreso' COLLATE utf8mb4_unicode_ci AS type,
                    '' COLLATE utf8mb4_unicode_ci AS created_at,
                    'Venta' COLLATE utf8mb4_unicode_ci AS movement_type,
                    'Venta' COLLATE utf8mb4_unicode_ci AS description,
                    SUM(p.value) AS amount,
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    u.name COLLATE utf8mb4_unicode_ci AS username
                FROM sale s
                JOIN users u ON u.id = s.users_id
                JOIN payment p ON p.sale_id = s.id
                JOIN cash_opening co ON co.id = s.cash_opening_id
                WHERE u.id = ? AND co.cash_id = ? 
                GROUP BY  p.method, u.name
            ) AS union_result
            ORDER BY created_at DESC
        ";

        // Parámetros del query
        $params = [
            $users_id, $cash_opened->cash_id,  
            $users_id, $cash_opened->cash_id    
        ];
        // Ejecutar consulta paginada
        $results = collect(DB::select($sql, $params));
            $sumSql = "
            SELECT payment_method, SUM(amount) AS total
            FROM (
                -- Movimientos
                SELECT 
                    m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                    m.amount
                FROM movement m
                WHERE m.users_id = ? AND m.cash_id = ?

                UNION ALL

                -- Ventas
                SELECT 
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    p.value AS amount
                FROM sale s
                JOIN payment p ON p.sale_id = s.id
                JOIN cash_opening co ON co.id = s.cash_opening_id
                WHERE s.users_id = ? AND co.cash_id = ?
            ) AS all_movements
            GROUP BY payment_method
        ";
        $sums = DB::select($sumSql, [
            $users_id, $cash_opened->cash_id,  
            $users_id, $cash_opened->cash_id   
        ]);



        $payment_methods = DB::table("config")
                    ->where('key',"=","payment_methods")
                    ->first();

        $current_cash_balance = CashBalance::where("cash_id","=",$cash_opened->cash_id)
                        ->get();


        //verificar movimientos de caja menor
        
        $sqlMCM = " 
            SELECT 
                m.type COLLATE utf8mb4_unicode_ci AS type,
                m.created_at,
                mt.name COLLATE utf8mb4_unicode_ci AS movement_type,
                m.description COLLATE utf8mb4_unicode_ci AS description,
                m.amount,
                m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                us.name COLLATE utf8mb4_unicode_ci AS username
            FROM movement m
            JOIN movement_types mt ON mt.id = m.movement_type_id
            JOIN users us ON us.id = m.users_id
            JOIN cash ca ON ca.id = m.cash_id
            WHERE us.id = ? 
            AND m.created_at >= ? 
            AND m.created_at <= ? 
            AND m.cash_id = ?
        "; 

        $petty_cash = DB::select($sqlMCM, [
            $users_id,
            date("Y-m-d") . " 00:00:00",
            date("Y-m-d") . " 23:59:59",
            1
        ]);


        return view('sale.cash.close',
        [
        'movements'=>$results,
        'totals'=>$sums,
        'payment_methods'=>explode(",",$payment_methods->value),
        'cash_opening'=>$cash_opened,
        'last_balances'=>$last_balances,
        'current_balances'=>$current_cash_balance,
        'petty_cash'=>$petty_cash
        ]);
    } 

    public function cash_close(Request $request,$id=null)
    {
        $cash_opening = CashOpening::
             where("users_id","=",auth()->user()->id)
             ->where("status","=","open")
             ->orderBy('created_at', 'desc')
             ->first();
        
        if(strcmp($request->getMethod(),"GET")==0){

            if($cash_opening){
                return $this->summary_cash_close();
            }
            else
            {

                $cash_registers = DB::table('config')
                          ->where('key','=','cash_registers')
                          ->first();

                $cash_locations = DB::table('config')
                                ->where('key','=','cash_locations')
                                ->first();
                return view('sale.cash.create',["cash_registers"=>explode(",",$cash_registers->value),"cash_locations"=>explode(",",$cash_locations->value)]);
            }
        }else{

            $end_balaces = DB::table("cash_balance")
                            ->where("cash_id","=",$cash_opening->cash_id)
                            ->get();

             if($cash_opening){
                 $cash_opening->summary = json_encode($request->only(['m50','m100','m200','m500','m1000','b2000','b5000','b10000','b20000','b50000','b100000']));
                 $cash_opening->status = "close";
                 $cash_opening->closed_at = date('Y-m-d H:i:s');
                 $cash_opening->end_amount = $request->post("total_close_amount");
                 $cash_opening->end_balances = json_encode($end_balaces);
                 $cash_opening->update();
                return response()->redirectTo("sale/cash_opening");
             }
        }
        
    }


}
