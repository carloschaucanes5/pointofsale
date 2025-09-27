<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
use App\Models\CashBalance;
use App\Models\Movement;
use App\Models\MovementType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Termwind\Components\Raw;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redirect;
use stdClass;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

       public function index(Request $request)
        {
            $payment_methods = DB::table("config")
                               ->where('key',"=","payment_methods")
                               ->first();
            $cashes = DB::table('cash as c')
                  ->select("c.id","c.name")
                  ->get();

            $cash_selected = $request->input('cash_id') ? $request->input('cash_id') : "";
            $type = $request->input('type')?$request->input('type'):"";
            $from = $request->input('from') . ' 00:00:00';
            $to = $request->input('to') . ' 23:59:59';
            if($request->input('from')=="" &&  $request->input('to')==""){
                $from = date('Y-m-d').$from;
                $to = date('Y-m-d').$to;
            }

            // Parámetros de paginación
            $page = request('page', 1);
            $perPage = 6;
            $offset = ($page - 1) * $perPage;

            // Consulta paginada

            //filtrar por tipo de movimiento y caja
            $filter = "";
            $filter_params = [];
            if($type != "" && $cash_selected != ""){
                $filter = " AND (m.type = ? AND c.id = ?)";
                $filter_params = [$type,$cash_selected];
            }else if($type != ""){
                $filter = " AND m.type = ?";
                $filter_params = [$type];
            }else if($cash_selected != ""){
                $filter = " AND c.id = ?";
                $filter_params = [$cash_selected];
            }else{
                $filter = "";
                $filter_params = [];
            }
                
            $sql = "
                SELECT * FROM (

                    SELECT 
                        m.type COLLATE utf8mb4_unicode_ci AS type,
                        m.created_at,
                        mt.name COLLATE utf8mb4_unicode_ci AS movement_type,
                        m.description COLLATE utf8mb4_unicode_ci AS description,
                        m.amount,
                        m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                        us.name COLLATE utf8mb4_unicode_ci AS username,
                        c.name  COLLATE utf8mb4_unicode_ci as name_cash
                    FROM movement m
                    JOIN cash c ON c.id = m.cash_id
                    JOIN movement_types mt ON mt.id = m.movement_type_id
                    JOIN users us ON us.id = m.users_id
                    WHERE m.created_at >= ? AND m.created_at <= ? $filter
                ) AS union_result
                ORDER BY created_at DESC
                LIMIT $perPage OFFSET $offset
            ";

            // Parámetros del query
            $params = array_merge([$from, $to], $filter_params);

            // Ejecutar consulta paginada
            $results = collect(DB::select($sql, $params));



            // Consulta para contar total de resultados sin LIMIT
            $countSql = "
                SELECT COUNT(*) AS total FROM (
                
                    SELECT m.created_at
                    FROM movement m
                    JOIN cash c ON c.id = m.cash_id
                    WHERE m.created_at >= ? AND m.created_at <= ? $filter
                ) AS total_result
            ";
            $total = DB::selectOne($countSql,$params)->total;

            $sumSql = "
                SELECT payment_method, SUM(amount) AS total
                FROM (

                    -- Movimientos
                    SELECT 
                        m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                        m.amount
                    FROM movement m
                    JOIN cash c ON c.id = m.cash_id
                    WHERE m.created_at BETWEEN ? AND ? $filter


                ) AS all_movements
                GROUP BY payment_method
            ";
            $sums = DB::select($sumSql,$params);

            // Crear el paginador manual
            $paginator = new LengthAwarePaginator(
                $results,
                $total,
                $perPage,
                $page,
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );


            return view('movement.movement.index',
                [
                    'movements'=>$results,
                    'from'=>explode(' ',$from)[0],
                    'to'=>explode(' ',$to)[0],
                    'type'=>$type,
                    'payment_methods'=>explode(",",$payment_methods->value),
                    'array_sums'=>$sums,
                    'paginator'=>$paginator,
                    'cashes'=>$cashes,
                    'cash_selected'=>$cash_selected
                 ]
            );
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cashes = DB::table('cash as c')
                  ->select("c.id","c.name")
                  ->get();   


        /*$cash = DB::table('cash_opening')
                    ->where('users_id','=',auth()->user()->id)
                    ->where('status','=','open')
                    ->first();
        if(!$cash){
            return back()->withErrors(['error' => 'El usuario no ha realizado apertura de caja'])->withInput();
        }*/
        $methods = DB::table('config')
                   ->where('key','=','payment_methods')
                   ->first();
        return view('movement.movement.create',['methods'=>explode(',',$methods->value),'cashes'=>$cashes]);
    }

    public function getTypesByCategory($type)
    {
        $types = DB::table('movement_types')
                ->where('type', '=', $type)
                ->orderBy('name')->get();
        return response()->json($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
 
            DB::beginTransaction();

            $validated = $request->validate([
            'type' => 'required|in:egreso,ingreso',
            'movement_type_id' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'cash_id' => 'required',
            ]);

            //---------------------------------------
            $cash_balance = CashBalance::where('cash_id','=',$validated['cash_id'])
            ->where("method","=",$validated['payment_method'])
            ->first();
            if($validated['type'] == "egreso"){
                if($cash_balance){
                    if($cash_balance->balance < $validated['amount']){
                        DB::rollBack();
                        return redirect()
                                ->back()
                                ->withErrors(['El monto del egreso no puede ser mayor al saldo actual de la caja'])
                                ->withInput();
                    }
                }else{
                    DB::rollBack();
                    return redirect()
                            ->back()
                            ->withErrors(['No hay saldo en la caja para realizar el egreso'])
                            ->withInput();
                }
            }
            //---------------------------------------
            $validated['users_id'] = auth()->user()->id;
            $amount = floatval($request->post("amount"));
            if($request->post("type") == "egreso"){
                 $amount = $amount * (-1);
            }
            $cash_opened = DB::table("cash_opening as co")
                ->where('co.users_id','=',auth()->user()->id)
                ->where('co.status','=','open')
                ->first();
            if($validated['cash_id'] == 3){
                if(!$cash_opened){
                    DB::rollBack();
                    return redirect()
                            ->back()
                            ->withErrors(['El usuario no ha realizado apertura de caja registradora'])
                            ->withInput();
                }
                $validated['table_identifier'] = "cash_opening-".$cash_opened->id;

            }
            $validated['amount'] = $amount;
            $validated['cash_opening_id'] = $cash_opened?$cash_opened->id : null;

            $movement = Movement::create($validated);
            if($movement){
                $cash_balance = CashBalance::where('cash_id','=',$movement->cash_id)
                            ->where("method","=",$validated['payment_method'])
                            ->first();
                if($cash_balance){
                    $cash_balance->balance = $cash_balance->balance + $amount;
                    $cash_balance->save();
                }
                else
                {
                    $cash_balance1 = new CashBalance();
                    $cash_balance1->cash_id = $movement->cash_id;
                    $cash_balance1->method = $validated['payment_method'];
                    $cash_balance1->balance = $amount;
                    $cash_balance1->save(); 
                }
                //validar si el tipo de moviemintpo es un trslado entre cajas
                if($validated['movement_type_id'] == 24 || $validated['movement_type_id'] == 25 || $validated['movement_type_id'] == 26){
                    $this->movements_box($validated['movement_type_id'],$amount,$validated['cash_id'],$validated['payment_method']);
                }
                DB::commit();
            }
            else
            {
                DB::rollBack();
                return back()->withErrors(['error' => 'No se pudo registrar el movimiento'])->withInput();
            }
            return redirect()->route('movement.index');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            Log::error('Error al abrir caja: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error'])->withInput();
        }
    }

public function movements_box($movement_type_id,$value,$cash_id,$method)
{
    //$movement_type_id 24 traslado a caja menor, entra a caja menor como ingreso
    //$movement_type_id 25 traslado a caja registradora, entra a caja registradora como ingreso
    //$movement_type_id 26 traslado a caja general, entra a caja generak como ingreso
    //esos ingresos afectas al balance de la caja
    $box = '';
    if($cash_id == 1){
        $box = 'Proviene de un Traslado desde Caja Menor';
    }
    if($cash_id == 2){
        $box = 'Proviene de un Traslado desde Caja General';
    }
    if($cash_id == 3){
        $box = 'Proviene de un Traslado desde Caja Registradora';
    }
    $movement = new Movement();
    $movement->type = "ingreso";
    if($movement_type_id == 24){
        $movement->description = $box ;
        $movement->cash_id = 1; //caja menor        
        $movement->movement_type_id = 24;
    }
    if($movement_type_id == 25){
        $movement->description = $box ;
        $movement->cash_id = 3; //caja registradora        
        $movement->movement_type_id = 25;
    }
    if($movement_type_id == 26){
        $movement->description = $box;
        $movement->cash_id = 2; //caja general        
        $movement->movement_type_id = 26;       
    }
    $movement->amount = abs($value);
    $movement->payment_method = $method;
    $movement->users_id = auth()->user()->id;
 
    //si hay apertura de caja, asignar el id
    $cash_opened = DB::table("cash_opening as co")
                ->where('co.users_id','=',auth()->user()->id)
                ->where('co.status','=','open')
                ->first();
    $movement->cash_opening_id = $cash_opened ?$cash_opened->id : null;
    $movement->table_identifier = null;
    $movement->save();
    $cash_balance = CashBalance::where('cash_id','=',$movement->cash_id )
                ->where("method","=",$method)
                ->first();
    if($cash_balance){
        //summar al saldo actual
        $cash_balance->balance = $cash_balance->balance + abs($value);
        $cash_balance->save(); 
    }else{
        $cash_balance1 = new CashBalance();
        $cash_balance1->cash_id = $movement->cash_id;
        $cash_balance1->method = $method;
        $cash_balance1->balance = abs($value);
        $cash_balance1->save();
    }     
}

public function filterByDate(Request $request)
{
    $request->validate([
        'from' => 'nullable|date',
        'to' => 'nullable|date|after_or_equal:from',
    ]);

    $query = Movement::query()->with(['users']);

    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->from);
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->to);
    }

    $movements = $query->orderBy('created_at', 'desc')
        ->paginate(10)
        ->appends($request->query()); // <--- clave para mantener los filtros

    return view('movement.movement.index', compact('movements'));
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

}
