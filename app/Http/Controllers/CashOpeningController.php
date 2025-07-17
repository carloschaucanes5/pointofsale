<?php

namespace App\Http\Controllers;


use App\Models\CashOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashOpeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $openings = CashOpening::with('user')->orderBy('opened_at', 'desc')->get();
        return view('cash_openings.index', compact('openings'));
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
        $request->validate([
            'start_amount' => 'required|numeric|min:0',
            'cashbox_name' => 'required|string|max:100',
            'location' => 'nullable|string|max:100',
            'observations' => 'nullable|string',
        ]);

        CashOpening::create([
            'user_id' => Auth::id(),
            'start_amount' => $request->start_amount,
            'opened_at' => now(),
            'cashbox_name' => $request->cashbox_name,
            'location' => $request->location,
            'observations' => $request->observations,
            'status' => 'open',
        ]);

        return redirect()->route('cash-openings.index')->with('success', 'Caja abierta correctamente.');
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
