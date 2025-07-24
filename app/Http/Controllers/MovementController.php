<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
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

            $movements_sale = DB::table('sale as s')
                ->join('users as u', 'u.id', '=', 's.users_id')
                ->join('payment as p', 'p.sale_id', '=', 's.id')
                ->where('s.created_at', '>=', $from)
                ->where('s.created_at', '<=', $to)
                ->select('p.method', 'u.name as username', DB::raw('SUM(p.value) as amount'))
                ->groupBy('p.method', 'u.name')
                ->get();

            


            $movements = DB::table('movement as m')
                ->join('movement_types as mt','mt.id','=','m.movement_type_id')
                ->join('users as us','us.id',"=","m.users_id")
                ->select('m.type','m.created_at','mt.name as movement_type','m.description','m.amount','m.payment_method','us.name as username')
                ->where('m.created_at','>=',$from)
                ->where('m.created_at','<=',$to)
                ->where(function($query) use ($type){
                    $query->where('m.type','like','%'.$type.'%');
                })
                ->orderBy('m.created_at', 'desc')
                ->paginate(7);
            return view('movement.movement.index',
                [
                    'movements'=>$movements,
                    'from'=>explode(' ',$from)[0],
                    'to'=>explode(' ',$to)[0],
                    'type'=>$type,
                    'payment_methods'=>explode(",",$payment_methods->value),
                    'movements_sale'=>$movements_sale
                 ]
            );
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $methods = DB::table('config')
                   ->where('key','=','payment_methods')
                   ->first();
        return view('movement.movement.create',['methods'=>explode(',',$methods->value)]);
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
            $validated = $request->validate([
            'type' => 'required|in:egreso,ingreso',
            'movement_type_id' => 'required|string|min:0',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            ]);
            $validated['users_id'] = auth()->user()->id;
            $cash = DB::table('cash_opening')
                    ->where('users_id','=',auth()->user()->id)
                    ->where('status','=','open')
                    ->first();
            if($cash){
                $validated['cash_opening_id'] = $cash->id;
                $movement = Movement::create($validated);
                /*return response()->json([
                    'success' => true,
                    'message' => 'Movimiento registrado correctamente',
                    'data' => $movement
                ]);*/
                return redirect()->route('movement.index');
            }
            else
            {
                return back()->withErrors(['error' => 'El usuario no ha realizado apertura de caja'])->withInput();
            }

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
