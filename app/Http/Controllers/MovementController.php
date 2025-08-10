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
                    WHERE m.created_at >= ? AND m.created_at <= ? AND m.type LIKE ?
                    
                    UNION ALL
                    
                    SELECT 
                        'ingreso' COLLATE utf8mb4_unicode_ci AS type,
                        '' COLLATE utf8mb4_unicode_ci AS created_at,
                        'Venta' COLLATE utf8mb4_unicode_ci AS movement_type,
                        '' COLLATE utf8mb4_unicode_ci AS description,
                        SUM(p.value) AS amount,
                        p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                        u.name COLLATE utf8mb4_unicode_ci AS username
                    FROM sale s
                    JOIN users u ON u.id = s.users_id
                    JOIN payment p ON p.sale_id = s.id
                    WHERE s.created_at >= ? AND s.created_at <= ? AND 'ingreso' LIKE ?
                    GROUP BY  p.method, u.name
                ) AS union_result
                ORDER BY created_at DESC
                LIMIT $perPage OFFSET $offset
            ";

            // Parámetros del query
            $params = [
                $from, $to, "%$type%", // para movement
                $from, $to, "%$type%"            // para sale
            ];

            // Ejecutar consulta paginada
            $results = collect(DB::select($sql, $params));



            // Consulta para contar total de resultados sin LIMIT
            $countSql = "
                SELECT COUNT(*) AS total FROM (
                
                    SELECT m.created_at
                    FROM movement m
                    WHERE m.created_at >= ? AND m.created_at <= ? AND m.type LIKE ?
                    
                    UNION ALL
                    
                    SELECT s.created_at
                    FROM sale s
                    WHERE s.created_at >= ? AND s.created_at <= ? AND 'ingreso' LIKE  ?
                    GROUP BY s.created_at, s.users_id
                ) AS total_result
            ";
            $total = DB::selectOne($countSql, [
                $from, $to, "%$type%",
                $from, $to, "%$type%"
            ])->total;

            $sumSql = "
                SELECT payment_method, SUM(amount) AS total
                FROM (

                    -- Movimientos
                    SELECT 
                        m.payment_method COLLATE utf8mb4_unicode_ci AS payment_method,
                        m.amount
                    FROM movement m
                    WHERE m.created_at BETWEEN ? AND ?
                    AND m.type like ?

                    UNION ALL

                    -- Ventas
                    SELECT 
                        p.method COLLATE utf8mb4_unicode_ci AS payment_method,
                        p.value AS amount
                    FROM sale s
                    JOIN payment p ON p.sale_id = s.id
                    WHERE s.created_at BETWEEN ? AND ?
                    AND 'ingreso' like ?
                ) AS all_movements
                GROUP BY payment_method
            ";
            $sums = DB::select($sumSql, [
                $from, $to, '%'.$type.'%',   // movement
                $from, $to, '%'.$type.'%'    // sale
            ]);



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
                    'paginator'=>$paginator
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


        $cash = DB::table('cash_opening')
                    ->where('users_id','=',auth()->user()->id)
                    ->where('status','=','open')
                    ->first();
        if(!$cash){
            return back()->withErrors(['error' => 'El usuario no ha realizado apertura de caja'])->withInput();
        }
        $methods = DB::table('config')
                   ->where('key','=','payment_methods')
                   ->first();
        return view('movement.movement.create',['methods'=>explode(',',$methods->value),'cashes'=>$cashes]);
    }

    public function getTypesByCategory($type)
    {
        $types = DB::table('movement_types')
        ->where('type', $type)
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
            'cash_id' => 'required|numeric|min:1',
            ]);
            $validated['users_id'] = auth()->user()->id;
            $amount = floatval($request->post("amount"));
            if($request->post("type") == "egreso"){
                 $amount = $amount * (-1);
            }
            if( $validated['cash_id'] == 3){
                $cash_opened = DB::table("cash_opening as co")
                             ->where('co.users_id','=',auth()->user()->id)
                             ->where('co.status','=','open')
                             ->first();
                $validated['table_identifier'] = "cash_opening-".$cash_opened->id;

                if(!$cash_opened){
                    DB::rollBack();
                    return back()->withErrors(['error' => 'El usuario no ha realizado apertura de caja'])->withInput();

                }
            }
            $validated['amount'] = $amount;          
            $movement = Movement::create($validated);
            if($movement){
                $cash_balance = CashBalance::where('cash_id','=',$movement->cash_id)
                            ->where("method","=",$validated['payment_method'])
                            ->first();
                if($cash_balance){
                    $cash_balance->balance = $cash_balance->balance + $amount;
                    $cash_balance->save();
                    DB::commit();
                }
                else
                {
                    $cash_balance1 = new CashBalance();
                    $cash_balance1->cash_id = $movement->cash_id;
                    $cash_balance1->method = $validated['payment_method'];
                    $cash_balance1->balance = $amount;
                    $cash_balance1->save();
                     DB::commit();
                }
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
