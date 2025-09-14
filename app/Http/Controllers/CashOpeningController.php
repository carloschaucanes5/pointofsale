<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
use App\Models\CashBalance;
use App\Models\Movement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use stdClass;
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
        $cash_opening_id = $id;
        $cash_opened = DB::table('cash_opening as co')
              ->join('cash as c','c.id','=','co.cash_id')
              ->join('users as us','us.id','=','co.users_id')
              ->where("co.id","=",$cash_opening_id)
              ->select("c.name as cash_name","co.id","co.users_id","co.start_amount","co.opened_at","co.observations","co.status","co.created_at","co.cash_id","co.last_balances",'us.name as user_name')
             ->orderBy('created_at', 'desc')
             ->first();
        $users_id = $cash_opened->users_id;
       

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
                WHERE us.id = ? AND m.cash_id = ? AND (m.table_identifier = ? OR m.cash_opening_id = ?)
                
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
                WHERE u.id = ? AND co.cash_id = ? AND  s.cash_opening_id = ? 
                GROUP BY  p.method, u.name
            ) AS union_result
            ORDER BY created_at DESC
        ";

        // Parámetros del query
        $params = [
            $users_id, 3,"cash_opening-".$cash_opened->id, $cash_opened->id, 
            $users_id, 3 ,$cash_opened->id    
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
                WHERE m.users_id = ? AND m.cash_id = ? and (m.table_identifier = ? or cash_opening_id = ?)

                UNION ALL

                -- Ventas
                SELECT 
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    p.value AS amount
                FROM sale s
                JOIN payment p ON p.sale_id = s.id
                JOIN cash_opening co ON co.id = s.cash_opening_id
                WHERE s.users_id = ? AND co.cash_id = ? AND s.cash_opening_id = ?
            ) AS all_movements
            GROUP BY payment_method
        ";
        $sums = DB::select($sumSql, [
            $users_id, 3, "cash_opening-".$cash_opened->id,$cash_opened->id,
            $users_id,3 ,$cash_opened->id   
        ]);



        $payment_methods = DB::table("config")
                    ->where('key',"=","payment_methods")
                    ->first();



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
            WHERE 
             m.created_at >= ? 
            AND m.created_at <= ? 
            AND m.cash_id = ?
        "; 

        $petty_cash = DB::select($sqlMCM, [
            date("Y-m-d 00:00:00",strtotime($cash_opened->created_at)),
            date("Y-m-d 23:59:59",strtotime($cash_opened->created_at)),
            1
        ]);

        //traer la primera apertura de caja del dia

        $first_cash_opened_day = DB::table('cash_opening as co')
                             ->whereBetween('co.created_at',[date("Y-m-d 00:00:00",strtotime($cash_opened->created_at)),date("Y-m-d 23:59:59",strtotime($cash_opened->created_at))])
                             ->orderBy("co.created_at")
                             ->first();
         $last_balances = json_decode($first_cash_opened_day->last_balances);

        $array_totals_movements = [];
        $methods = explode(",",$payment_methods->value);
        foreach($methods as $me){
            $obj1 = new stdClass();
            $obj1->method = $me;
            $totalmethod = 0;
            foreach($petty_cash as $petty){
                if($petty->payment_method == $me){
                    $totalmethod = $totalmethod + $petty->amount;
                }
            }
            foreach($last_balances as $balance){
                if($balance->cash_id == 1 && $balance->method == $me){
                    $totalmethod = $totalmethod + $balance->balance;
                }
            }
            $obj1->total = $totalmethod;
            array_push($array_totals_movements,$obj1);
        }
        
        return view('sale.cash.show',
        [
        'movements'=>$results,
        'totals'=>$sums,
        'payment_methods'=>explode(",",$payment_methods->value),
        'cash_opening'=>$cash_opened,
        'last_balances'=>$last_balances,
        'current_balances'=>[],
        'petty_cash'=>$petty_cash,
        'array_totals_movements'=>$array_totals_movements
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
                WHERE us.id = ? AND m.cash_id = ? AND (m.table_identifier = ? or cash_opening_id = ?)
                
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
                WHERE u.id = ? AND co.cash_id = ? AND  s.cash_opening_id = ? 
                GROUP BY  p.method, u.name
            ) AS union_result
            ORDER BY created_at DESC
        ";

        // Parámetros del query
        $params = [
            $users_id, 3,"cash_opening-".$cash_opened->id,$cash_opened->id,  
            $users_id, 3 ,$cash_opened->id    
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
                WHERE m.users_id = ? AND m.cash_id = ? and (m.table_identifier = ? or cash_opening_id = ?)

                UNION ALL

                -- Ventas
                SELECT 
                    p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                    p.value AS amount
                FROM sale s
                JOIN payment p ON p.sale_id = s.id
                JOIN cash_opening co ON co.id = s.cash_opening_id
                WHERE s.users_id = ? AND co.cash_id = ? AND s.cash_opening_id = ?
            ) AS all_movements
            GROUP BY payment_method
        ";
        $sums = DB::select($sumSql, [
            $users_id, 3, "cash_opening-".$cash_opened->id,$cash_opened->id,  
            $users_id,3 ,$cash_opened->id   
        ]);



        $payment_methods = DB::table("config")
                    ->where('key',"=","payment_methods")
                    ->first();

        $current_cash_balance = CashBalance::where("cash_id","=",1)
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
            WHERE 
             m.created_at >= ? 
            AND m.created_at <= ? 
            AND m.cash_id = ?
        "; 

        $petty_cash = DB::select($sqlMCM, [
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
            if(isset($id)){
              return $this->view_cash_summary($id);
            }

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
            DB::beginTransaction();
            $end_balaces = DB::table("cash_balance")
                            ->where("cash_id","=",1)
                            ->get();

             if($cash_opening){
                 $cash_opening->summary = json_encode($request->only(['m50','m100','m200','m500','m1000','b2000','b5000','b10000','b20000','b50000','b100000']));
                 $cash_opening->status = "close";
                 $cash_opening->closed_at = date('Y-m-d H:i:s');
                 $cash_opening->end_amount = $request->post("total_close_amount");
                 $cash_opening->end_balances = json_encode($end_balaces);
                 $cash_opening->update();
                //-----------------------------------------------------
                $sumSql = "
                    SELECT payment_method, SUM(amount) AS total
                    FROM (
                        -- Movimientos
                        SELECT 
                            m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                            m.amount
                        FROM movement m
                        WHERE m.users_id = ? AND m.cash_id = ? AND m.table_identifier = ?

                        UNION ALL

                        -- Ventas
                        SELECT 
                            p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                            p.value AS amount
                        FROM sale s
                        JOIN payment p ON p.sale_id = s.id
                        JOIN cash_opening co ON co.id = s.cash_opening_id
                        WHERE s.users_id = ? AND co.cash_id = ? AND s.cash_opening_id = ?
                    ) AS all_movements
                    GROUP BY payment_method
                ";
                $sums = DB::select($sumSql, [
                    auth()->user()->id, 3, "cash_opening-".$cash_opening->id, 
                    auth()->user()->id, 3, $cash_opening->id  
                ]);


                foreach($sums as $sum){
                    $movement = new Movement();
                    $movement->users_id = auth()->user()->id;
                    $movement->cash_id = 1;
                    $movement->type = "ingreso";
                    $movement->amount = $sum->total;
                    $movement->payment_method = $sum->payment_method;
                    $movement->description = "Cierre de caja por ". auth()->user()->name;
                    $movement->movement_type_id = 1; // Ingreso
                    $movement->save();
                    if($movement){
                        $cash_balance = CashBalance::where('cash_id','=',1)
                                    ->where("method","=",$sum->payment_method)
                                    ->first();
                        if($cash_balance){
                            $cash_balance->balance = $cash_balance->balance + $sum->total;
                            $cash_balance->save();
                        }
                        else
                        {
                            $cash_balance1 = new CashBalance();
                            $cash_balance1->cash_id = 1;
                            $cash_balance1->method = $sum->payment_method;
                            $cash_balance1->balance = $sum->total;
                            $cash_balance1->save();
                        }
                     }
                    else
                    {
                        DB::rollBack();
                        return response()->json([
                            "success"=>false,
                            "message"=>"Error al registrar el movimiento de cierre de caja"
                        ],Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                     $balances = CashBalance::where("cash_id","=",3)
                                ->get();
                    foreach($balances as $balance){
                        $balance->balance = 0;
                        $balance->update();
                    }
                    DB::commit();
                }

                

                //-----------------------------------------------------
                
                
                return response()->redirectTo("sale/cash_opening");
             }
        }
        
    }

    function general_report(Request $request){
            $types_movement = DB::table("movement_types")
                            ->get();
            $from = $request->input('from') . ' 00:00:00';
            $to = $request->input('to') . ' 23:59:59';
            if($request->input('from')=="" &&  $request->input('to')==""){
                $from = date('Y-m-d').$from;
                $to = date('Y-m-d').$to;
            }
            $type_movement = $request->input("type_movement");
            $detail = $request->input("text_search");
            $filter = "";
            $filterData = "";
            if($type_movement!=""){
                $filter = "AND m.movement_type_id = ? ";
                $filterData = $type_movement;
            }else{
                $filter = "AND m.description LIKE ? ";
                $filterData = "%".$detail."%";
            }
            $sql = "
                SELECT 
                    m.type,
                    m.created_at,
                    mt.name AS movement_type,
                    m.description,
                    SUM(m.amount) AS total
                FROM movement m
                JOIN movement_types mt ON mt.id = m.movement_type_id
                JOIN users us ON us.id = m.users_id
                WHERE m.created_at BETWEEN ? AND ? ".$filter." 
                AND m.cash_id = 1
                GROUP BY m.type, m.created_at, mt.name, m.description
                ORDER BY m.created_at DESC;
                ";
        $params = [
            $from, $to, $filterData
        ];
        // Ejecutar consulta paginada
        $results = collect(DB::select($sql, $params));

        //total egresos
        $total_spent = 0;
        foreach($results as $res){
            if($res->type == 'egreso'){
                $total_spent = $total_spent + $res->total;
            }
        }

        $total_income = 0;
        foreach($results as $res){
            if($res->type == 'ingreso'){
                 $total_income  =  $total_income + $res->total;
            }
        }
        return view("report.general.general",[
            'from'=>$request->input('from')?$request->input('from'):date('Y-m-d'),
            'to'=>$request->input('to')?$request->input('to'):date('Y-m-d'),
            'text_search'=>$request->input('text_search'),
            'movements'=>$results,
            'total_income'=>$total_income,
            'total_spent'=>$total_spent,
            'types_movement'=>$types_movement 
        ]);
    }

}
