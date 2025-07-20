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
                ->select("co.id","co.opened_at","co.start_amount","co.cashbox_name","co.location","co.observations","co.status","co.end_amount","co.closed_at","us.name")
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

   public function cash_close(Request $request,$id=null)
    {
        return view('sale.cash.close');
    }
}
