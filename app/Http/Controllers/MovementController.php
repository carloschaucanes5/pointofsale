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
               $from = $request->input('from') . ' 00:00:00';
               $to = $request->input('to') . ' 23:59:59';
            $movements = Movement::
                where('created_at','>=',$from)
                ->where('created_at','<=',$to)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('movement.movement.index',['movements'=>$movements]);
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('movement.movement.create');
    }

    public function getTypesByCategory($type)
    {
        $types = MovementType::where('type', $type)->orderBy('name')->get();
        return response()->json($types);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:50',
            'cash_opening_id' => 'nullable|exists:cash_openings,id',
            ]);
            $validated['users_id'] = auth()->user()->id;

            $movement = Movement::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Movimiento registrado correctamente',
                'data' => $movement
            ]);
            return redirect()->route('movement.movement.index');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            Log::error('Error al abrir caja: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error al abrir la caja.'])->withInput();
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

    public function cash_close(Request $request,$id=null)
    {
        if(strcmp($request->getMethod(),"GET")==0){
            $cash = CashOpening::
             where("users_id","=",auth()->user()->id)
             ->where("status","=","open")
             ->orderBy('created_at', 'desc')
             ->first();
            if($cash){
                return view('sale.cash.close');
            }
            else
            {
                return view('sale.cash');
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
                return response()->redirectTo("sale.cash_opening",200);
             }
        }
        
    }


}
