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

class CashOpeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */

       public function index(Request $request)
        {
            $search = $request->get('searchText');
            $openings = CashOpening::
                when($search, function ($query, $search) {
                    return $query->where('cashbox_name', 'like', "%$search%");
                })
                ->orderBy('opened_at', 'desc')
                ->paginate(5);

            return view('sale.cash.index', compact('openings', 'search'));
        }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cash_registers = DB::table('config')
                          ->where('key','=','cash_registers')
                          ->first();

        $cash_locations = DB::table('config')
                          ->where('key','=','cash_locations')
                          ->first();

        return view('sale.cash.create',["cash_registers"=>explode(",",$cash_registers->value),"cash_locations"=>explode(",",$cash_locations->value)]);
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
        return redirect()->route('cash_opening.index');
    } catch (ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    } catch (Exception $e) {
        Log::error('Error al abrir caja: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Ocurrió un error al abrir la caja.'])->withInput();
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
}
