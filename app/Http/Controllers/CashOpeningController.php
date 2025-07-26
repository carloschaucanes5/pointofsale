<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
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
                        $cash_registers = DB::table('config')
                          ->where('key','=','cash_registers')
                          ->first();
                        $cash_locations = DB::table('config')
                          ->where('key','=','cash_locations')
                          ->first();
                        return view('sale.cash.create',["cash_registers"=>explode(",",$cash_registers->value),"cash_locations"=>explode(",",$cash_locations->value)]);
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
                'observations' => 'nullable|string',
            ]);
            $cash = new CashOpening();
            $cash->users_id = auth()->user()->id;
            $cash->start_amount = $request->start_amount;
            $cash->end_amount = 0;
            $cash->opened_at = date("Y-m-d H:s:i");
            $cash->cashbox_name = $request->cashbox_name;
            $cash->location = $request->location;
            $cash->observations = $request->observations;
            $cash->status = 'open';
            $cash->save();
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
        //
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
        $cash_opening_id = CashOpening::where("users_id","=",auth()->user()->id)
             ->orderBy('created_at', 'desc')
             ->first()->id;
            $sql = "
            SELECT * FROM (

                SELECT 
                    'ingreso' COLLATE utf8mb4_unicode_ci AS type,
                    ac.opened_at  AS created_at,
                    'Apertura de Caja' COLLATE utf8mb4_unicode_ci AS movement_type,
                        ac.observations COLLATE utf8mb4_unicode_ci AS description,
                        ac.start_amount as amount,
                        'efectivo' COLLATE utf8mb4_unicode_ci AS payment_method,
                        us.name COLLATE utf8mb4_unicode_ci AS username
                FROM cash_opening ac
                JOIN users us ON us.id = ac.users_id
                WHERE us.id = ? AND ac.id = ?

                UNION ALL

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
                WHERE us.id = ? AND m.cash_opening_id = ? 
                
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
                WHERE u.id = ? AND s.cash_opening_id = ? 
                GROUP BY  p.method, u.name
            ) AS union_result
            ORDER BY created_at DESC
        ";

        // Parámetros del query
        $params = [
            $users_id, $cash_opening_id, 
            $users_id, $cash_opening_id,  
            $users_id, $cash_opening_id,      
        ];
        // Ejecutar consulta paginada
        $results = collect(DB::select($sql, $params));

            $sumSql = "
            SELECT payment_method, SUM(amount) AS total
            FROM (
                -- Aperturas de caja
                SELECT 
                    'efectivo' COLLATE utf8mb4_unicode_ci AS payment_method,
                    ac.start_amount AS amount
                FROM cash_opening ac
                WHERE ac.users_id = ? AND ac.id = ?
                

                UNION ALL

                -- Movimientos
                SELECT 
                    m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                    m.amount
                FROM movement m
                WHERE m.users_id = ? AND m.cash_opening_id = ?

                UNION ALL

                -- Ventas
                SELECT 
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    p.value AS amount
                FROM sale s
                JOIN payment p ON p.sale_id = s.id
                WHERE s.users_id = ? AND s.cash_opening_id = ?
            ) AS all_movements
            GROUP BY payment_method
        ";
        $sums = DB::select($sumSql, [
            $users_id, $cash_opening_id, 
            $users_id, $cash_opening_id,  
            $users_id, $cash_opening_id   
        ]);

        $payment_methods = DB::table("config")
                    ->where('key',"=","payment_methods")
                    ->first();
        return view('sale.cash.close',['movements'=>$results,'totals'=>$sums, 'payment_methods'=>$payment_methods]);
    } 

    public function cash_close(Request $request,$id=null)
    {
        
        if(strcmp($request->getMethod(),"GET")==0){
            $cash = CashOpening::
             where("users_id","=",auth()->user()->id)
             ->where("status","=","open")
             ->orderBy('created_at', 'desc')
             ->first();
            if($cash){
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
            $cash = CashOpening::where("users_id","=",auth()->user()->id)
             ->orderBy('created_at', 'desc')
             ->first();
             if($cash){
                $cash->summary = json_encode($request->only(['m50','m100','m200','m500','m1000','b2000','b5000','b10000','b20000','b50000','b100000']));
                $cash->status = "close";
                $cash->closed_at = date('Y-m-d H:i:s');
                $cash->end_amount = $request->post("total_close_value");
                $cash->update();
                return response()->redirectTo("sale/cash_opening");
             }
        }
        
    }


}
