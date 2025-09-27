<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherFormRequest;
use App\Models\Movement;
use App\Models\PaymentVoucher;
use App\Models\Voucher;
use App\Models\Supplier;
use App\Models\CashBalance;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;

use function Laravel\Prompts\table;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {}

    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        if($from == '' && $to == ''){
            $from = date('Y-m-d 00:00:00');
            $to = date('Y-m-d 23:59:59');
        }else{
            $from = date('Y-m-d 00:00:00',strtotime($from));
            $to = date('Y-m-d 23:59:59',strtotime($to));
        }
        if ($request) {
            $query = trim($request->get('searchText'));
            $vouchers = DB::table("voucher")
                ->join('person', 'voucher.supplier_id', '=', 'person.id')
                ->join('users', 'voucher.users_id', '=', 'users.id')
                ->select(
                    'voucher.id',
                    'voucher.voucher_number',
                    'voucher.description',
                    'voucher.total',
                    'voucher.photo',
                    'voucher.status',
                    'person.name as supplier_name',
                    'person.id as supplier_id',
                    'voucher.status_payment',
                    'voucher.updated_at',
                    'users.name as user_name',
                    'voucher.created_at'
                )
                ->where('voucher.status', '=', 1)
                ->where(function ($q) use ($query) {
                    $q->where('voucher.voucher_number', 'like', '%' . $query . '%')
                    ->orWhere('voucher.description', 'like', '%' . $query . '%')
                    ->orWhere('person.name','like','%'.$query.'%')
                    ->orWhere('users.name','like','%'.$query.'%');
                })
                ->whereBetween('voucher.created_at',[$from,$to])
                ->orderBy('voucher.id', 'desc')
                ->paginate(5)
                ->appends([
                    'from' => date('Y-m-d', strtotime($from)),
                    'to'   => date('Y-m-d', strtotime($to))
                ]);


            return view('purchase.voucher.index', ['vouchers' => $vouchers, 'searchText' => $query,'from'=>date('Y-m-d',strtotime($from)),'to'=>date('Y-m-d',strtotime($to))]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cashes = DB::table('cash')
            ->orderBy('name', 'asc')
            ->get();
            


        $payment_methods =DB::table('config')
            ->where("key","=","payment_methods")
            ->get()
            ->first();
        $suppliers = Supplier::where('person_type', 'supplier')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $status_payment = DB::table('config')
            ->where('key', 'status_payment')
            ->first();
        if ($status_payment) {
        //convertir el valor a un array viene separados por comas
            $status_payment_array = explode(',', $status_payment->value);
        } else {
            $status_payment_array = [];
        }
        return view("purchase.voucher.create", [
            "suppliers" => $suppliers,
            "status_payment" => $status_payment_array,
            'payment_methods'=>explode(",",$payment_methods->value),
            'cashes' => $cashes]);
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherFormRequest $request)
    { 
        try
        {
            DB::beginTransaction();
            if($request->get('cash_id') == 3){
                $cash = DB::table('cash_opening')
                        ->where('users_id','=',auth()->user()->id)
                        ->where('status','=','open')
                        ->first();
                if(!$cash){
                    return response()->json([
                        'success' => false,
                        'message' => 'No se ha abierto la caja para registrar el egreso.'
                    ], 500);
                }

            }


            $voucher = new Voucher();
            $voucher->voucher_number = $request->get('voucher_number');
            $voucher->total = $request->get('total');
            $voucher->description = $request->get('description');
            $voucher->supplier_id = explode("-",$request->get('supplier_id'))[0];
            $voucher->status_payment = $request->get('status_payment');
            $voucher->users_id = auth()->user()->id;
            $voucher->status = 1;
            $voucher->photo = '';
            $voucher->save();
            

            $methods = json_decode($request->get("methods"));
            foreach($methods as $met){
                $movement = new Movement();
                $decription="";
                if($met->method=="credito"){
                    $movement->type="ingreso";
                    $amount = floatval($met->value);
                    $decription="Ingreso de nueva cuenta de credito";
                }
                else{
                    $amount = floatval($met->value) * (-1);
                    $movement->type="egreso";
                    
                }

                $payment_voucher = new PaymentVoucher();
                $payment_voucher->voucher_id = $voucher->id;
                $payment_voucher->method = $met->method;
                $payment_voucher->value = $met->value;
                $payment_voucher->status = 1;
                $payment_voucher->cash_id = $request->get('cash_id');
                $payment_voucher->save();


                $movement->users_id = auth()->user()->id;
                $movement->cash_id = $request->get('cash_id');
                $movement->movement_type_id = 6;
                $movement->description = $decription."-".explode("-",$request->get('supplier_id'))[1]."(".$request->get('voucher_number').")";
                $movement->amount = $amount;
                $movement->payment_method = $met->method;
                $movement->table_identifier = "voucher-".$voucher->id;
                $movement->cash_opening_id = $request->get('cash_id') == 3 ? $cash->id : null;
                $movement->save();

                $cash_balance = CashBalance::where('cash_id','=',$request->get('cash_id'))
                            ->where("method","=",$met->method)
                            ->first();
                    if($cash_balance){
                        if($cash_balance->balance < abs($amount) &&  $movement->type == "egreso"){
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => 'No hay suficiente saldo en la caja para realizar el egreso con el método de pago '.$met->method.'.'
                            ], 500);
                            break;
                        }
                        $cash_balance->balance = $cash_balance->balance + $amount;
                        $cash_balance->save();
                    }
                    else
                    {
                        $cash_balance1 = new CashBalance();
                        $cash_balance1->cash_id = $request->get('cash_id');
                        $cash_balance1->method = $met->method;
                        $cash_balance1->balance = $amount;
                        $cash_balance1->save();
                    }
                     
            }

                   DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Factura creada correctamente',
                        'voucher' => $voucher
                    ], 201);
           /* 
            //registrar movimientos de caja
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $folder = public_path('images/purchase/voucher');
                // Crea la carpeta si no existe
                if (!file_exists($folder)) {
                    mkdir($folder, 0775, true);
                }
                $id = $voucher->id?$voucher->id:$voucher->voucher_number;
                $name = $id . '.' . $photo->getClientOriginalExtension();
                // Mueve el archivo al directorio público
                $movedFile = $photo->move($folder, $name);
                if($movedFile && file_exists($movedFile->getPathname())){
                    $voucher->photo = 'images/purchase/voucher/' . $name;
                    $voucher->save();
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Factura creada correctamente',
                        'voucher' => $voucher
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No existe la ruta de la imagen o no se ha podido mover el archivo.'
                    ], 500);
                }       
            }
            else
            {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha subido la imagen correctamente. Por favor, inténtelo de nuevo.'
                ], 500);
                
            }*/
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('purchase.voucher.show', ['voucher' => Voucher::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $suppliers = Supplier::where('person_type', 'supplier')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $status_payment = DB::table('config')
            ->where('key', 'status_payment')
            ->first();
        if ($status_payment) {
        //convertir el valor a un array viene separados por comas
            $status_payment_array = explode(',', $status_payment->value);
        } else {
            $status_payment_array = [];
        }
        return view("purchase.voucher.edit", ["voucher" => Voucher::findOrFail($id),
            "suppliers" => $suppliers,
            "status_payment" => $status_payment_array]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherFormRequest $request, $id)
    {
         try
        {
            DB::beginTransaction();
            $voucher = Voucher::findOrFail($id);
            $voucher->voucher_number = $request->get('voucher_number');
            $voucher->total = $request->get('total');
            $voucher->description = $request->get('description');
            $voucher->supplier_id = $request->get('supplier_id');
            $voucher->status_payment = $request->get('status_payment');
            $voucher->users_id = auth()->user()->id;
            $voucher->status = 1;
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $folder = public_path('images/purchase/voucher');
                // Crea la carpeta si no existe
                if (!file_exists($folder)) {
                    mkdir($folder, 0775, true);
                }
                $id = $voucher->id?$voucher->id:$voucher->voucher_number;
                $name = $id . '.' . $photo->getClientOriginalExtension();
                // Mueve el archivo al directorio público
                $movedFile = $photo->move($folder, $name);
                if($movedFile && file_exists($movedFile->getPathname())){
                    $voucher->photo = 'images/purchase/voucher/' . $name;
                    $voucher->update();
                    DB::commit();
                    return response()->json([
                        'success' => true,
                        'message' => 'Factura actualizada correctamente',
                        'voucher' => $voucher
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No existe la ruta de la imagen o no se ha podido mover el archivo.'
                    ], 500);
                
                }       
            }
            else
            {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha subido la imagen correctamente. Por favor, inténtelo de nuevo.'
                ], 500);
                
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->status = 0;
        $voucher->update();
        return Redirect::to("purchase/voucher");
    }

  public function historical(Request $request)
    {
        $payment_methods =DB::table('config')
            ->where("key","=","payment_methods")
            ->get()
            ->first();
        $cashes = DB::table('cash')
            ->orderBy('name', 'asc')
            ->get();

        $method_payment_selected = $request->get('method_payment');

        $status_payment = $request->get('status_payment');
        $from = $request->get('from');
        $to = $request->get('to');
        if($from == '' && $to == ''){
            $from = date('Y-m-d 00:00:00');
            $to = date('Y-m-d 23:59:59');
        }else{
            $from = date('Y-m-d 00:00:00',strtotime($from));
            $to = date('Y-m-d 23:59:59',strtotime($to));
        }
        if ($request) {
            $query = trim($request->get('searchText'));
            $vouchers = DB::table("payment_voucher as pv")
                ->join('voucher as v  as pv', 'pv.voucher_id', '=','v.id')
                ->join('cash', 'pv.cash_id', '=', 'cash.id')
                ->join('person', 'v.supplier_id', '=', 'person.id')
                ->join('users', 'v.users_id', '=', 'users.id')
                ->select(
                    'v.id',
                    'v.voucher_number',
                    'v.description',
                    'v.total',
                    'v.photo',
                    'v.status',
                    'person.name as supplier_name',
                    'person.id as supplier_id',
                    'v.status_payment',
                    'v.updated_at',
                    'users.name as user_name',
                    'v.created_at',
                    'pv.value as paid_amount',
                    'pv.method as payment_method',
                    'pv.cash_id',
                    'cash.name as cash_name',
                    'pv.id as payment_voucher_id'
                )
                ->where(function ($q) use ($query) {
                    $q->where('v.voucher_number', 'like', '%' . $query . '%')
                    ->orWhere('v.description', 'like', '%' . $query . '%')
                    ->orWhere('person.name','like','%'.$query.'%')
                    ->orWhere('users.name','like','%'.$query.'%');
                })
                ->where(function($q) use ($status_payment){
                    if($status_payment != ''){
                        $q->where('v.status_payment','=',$status_payment);
                    }
                })
                ->where(function($q) use ($method_payment_selected){
                    if($method_payment_selected != ''){
                        $q->where('pv.method','=',$method_payment_selected);
                    }
                })
                ->whereBetween('v.created_at',[$from,$to])
                ->orderBy('v.id', 'desc')
                ->paginate(5)
                ->appends([
                    'from' => date('Y-m-d', strtotime($from)),
                    'to'   => date('Y-m-d', strtotime($to))
                ]);


            return view('purchase.voucher.historical', ['vouchers' => $vouchers, 'searchText' => $query,'from'=>date('Y-m-d',strtotime($from)),'to'=>date('Y-m-d',strtotime($to)),'status_payment'=>$status_payment, 'payment_methods'=>explode(",",$payment_methods->value), 'method_payment_selected'=>$method_payment_selected,'cashes'=>$cashes]);
        }
    }

    public function pay(Request $request)
    {
        try{
            DB::beginTransaction();
            $voucher_amount = $request->get('amount');
            $id = $request->get('voucher_id');
            $cash_old = $request->get('cash_old');
            $cash_now = $request->get('cash_now');
            $method_payment = $request->get('method_payment');

            $payment_voucher = PaymentVoucher::findOrFail($request->get('payment_voucher_id'));
            $payment_voucher->method = $request->get('method_payment');
            $payment_voucher->cash_id = $request->get('cash_now');
            if(!$payment_voucher->update()){
                DB::rollBack();
                return Redirect::to("purchase/voucher/historical")->with('error','Error al actualizar el estado de la factura en el medio de pago');
            }


            $voucher = Voucher::findOrFail($id);
            $voucher->status_payment = 'contado';
            if(!$voucher->update()){
                DB::rollBack();
                return Redirect::to("purchase/voucher/historical")->with('error','Error al actualizar el estado de la factura.');
            }

            $balance_balance = DB::table("cash_balance")
                       ->where("cash_balance.cash_id","=",$cash_now)
                       ->where("cash_balance.method","=",$method_payment)
                       ->first();
            if($balance_balance){
                if(floatval($balance_balance->balance)  <  floatval($voucher_amount))
                {
                    DB::rollBack();
                    return Redirect::to("purchase/voucher/historical")->with('error','La caja seleccionada no tiene saldo');
                }
            }
            


            $movement = new Movement();
            $movement->type="egreso";
            $movement->users_id = auth()->user()->id;
            $movement->cash_id = $cash_old;
            $movement->movement_type_id = 29;
            $movement->description = "Pago de factura a credito =>(".$voucher->voucher_number.")";
            $movement->amount = floatval($voucher_amount) * (-1);
            $movement->payment_method = 'credito';
            $movement->table_identifier = "voucher-".$voucher->id;
            if(!$movement->save()){
                DB::rollBack();
                return Redirect::to("purchase/voucher/historical")->with('error','Error al registrar el movimiento de caja.');
            }

            $movement1 = new Movement();
            $movement1->type="egreso";
            $movement1->users_id = auth()->user()->id;
            $movement1->cash_id = $cash_now;
            $movement1->movement_type_id = 29;
            $movement1->description = "Pago de factura con ".$method_payment." =>(".$voucher->voucher_number.")";
            $movement1->amount = floatval($voucher_amount) * (-1);
            $movement1->payment_method = $method_payment;
            $movement1->table_identifier = "voucher-".$voucher->id;
            if(!$movement1->save()){
                DB::rollBack();
                return Redirect::to("purchase/voucher/historical")->with('error','Error al registrar el movimiento de caja.');
            }


            DB::commit();
            return Redirect::to("purchase/voucher/historical")->with('success','Factura pagada correctamente.');
        } catch (\Exception $e) {
x|            DB::rollBack();
            return Redirect::to("purchase/voucher/historical")->with('error','Error al pagar la factura: ' . $e->getMessage());
        }
    }
}
