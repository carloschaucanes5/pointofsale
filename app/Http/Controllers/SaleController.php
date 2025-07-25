<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SaleFormRequest;
use App\Models\IncomeDetail;
use App\Models\IncomeDetailHistorical;
use App\Models\Payment;
use App\Models\ReturnSale;
use Exception;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Sale;
use App\Models\SaleDetail;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $searchText = $request->get("searchText");
        $start_date = $request->get("start_date")?$request->get("start_date"):date('Y-m-d');
        $end_date = $request->get("end_date")?$request->get("end_date"):date('Y-m-d');
        if($request){
            $logo = DB::table('config')
                          ->where("key","=","logo")
                          ->get()
                          ->first();
            $company = DB::table('config')
                        ->where("key","like","company%")
                        ->select('key','value','alias')
                        ->get()
                        ->keyBy('key');
            if(trim($searchText)==""){
                $sales = DB::table("sale as sal")
                       ->join('person as pe','pe.id','=','sal.customer_id')
                       ->join("users as u","u.id","=","sal.users_id")
                       ->whereBetween("sal.created_at",[
                            date($start_date." 00:00:00"),
                            date($end_date." 23:59:59")
                       ])
                       ->select("sal.id","sal.change","sal.created_at","pe.name as customer_name","pe.address as customer_address","pe.phone as customer_phone","pe.email as customer_email","pe.document_type","pe.document_number","sal.tax","sal.sale_total","u.name as user_name","sal.payment_form")
                       ->orderBy('sal.id','desc')
                       ->paginate(6)
                       ->appends([
                            'searchText' => $searchText,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);
            }else{
                $sales = DB::table("sale as sal")
                    ->join('sale_detail as sde', 'sde.sale_id', '=', 'sal.id')
                    ->join('income_detail_historical as idh', 'idh.income_detail_id', '=', 'sde.income_detail_id')
                    ->join('product as pr', 'pr.id', '=', 'idh.product_id') 
                    ->join('person as pe', 'pe.id', '=', 'sal.customer_id')
                    ->join('users as u', 'u.id', '=', 'sal.users_id')
                    ->where(function($query) use ($searchText) {
                        $query->where('pr.name', 'like', '%' . $searchText . '%')
                            ->orWhere('pr.code', 'like', '%' . $searchText . '%');
                    })
                    ->whereBetween('sal.created_at', [
                        $start_date . ' 00:00:00',
                        $end_date . ' 23:59:59'
                    ])
                    ->select(
                        'sal.id',
                        'sal.change',
                        'sal.created_at',
                        'pe.name as customer_name',
                        'pe.address as customer_address',
                        'pe.phone as customer_phone',
                        'pe.email as customer_email',
                        'pe.document_type',
                        'pe.document_number',
                        'sal.tax',
                        'sal.sale_total',
                        'u.name as user_name',
                        'sal.payment_form'
                    )
                    ->orderBy('sal.id', 'desc')
                    ->paginate(6)
                    ->appends([
                        'searchText' => $searchText,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                    ]);
            }

            return view('sale.sale.index',['sales'=>$sales,'texto'=>$searchText,'start_date'=>$start_date,'end_date'=>$end_date,'company'=>$company,'logo'=>$logo]);
        }
    }

    /**
     * Buscar el producto en inventario sea por nombre o codigo de barras
     */

     public function search_product(string $textSearch=""){
        try{
            $searchText = trim($textSearch);
            $incomes_detail = DB::table('product as p')
            ->join('category as c','p.category_id','=','c.id')
            ->join('income_detail as ide','p.id','=','ide.product_id')
            ->select('ide.id','p.code','p.name','p.stock','p.description','p.image','p.status','c.category','p.presentation','p.concentration','p.laboratory','ide.purchase_price','ide.sale_price','ide.form_sale','ide.expiration_date','ide.quantity')
            ->where(function($query) use ($searchText){
                $query->where('p.name','like','%'.$searchText.'%')
                        ->orwhere('p.code','like','%'.$searchText.'%');
            })
            ->where('ide.quantity','>',0)
            ->orderBy('p.name','asc')
            ->paginate(2);
            return response()->json(['incomes_detail'=>$incomes_detail]); 
        }catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
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
            if($cash_opened)
            {
                $payment_methods =DB::table('config')
                                ->where("key","=","payment_methods")
                                ->get()
                                ->first();
                
                $payment_forms =DB::table('config')
                            ->where("key","=","payment_forms")
                            ->get()
                            ->first();

                $logo = DB::table('config')
                                ->where("key","=","logo")
                                ->get()
                                ->first();

                //extraer la infomacion de compania donde el key tiene como base la palabra company_name
                $company = DB::table('config')
                            ->where("key","like","company%")
                            ->select('key','value','alias')
                            ->get()
                            ->keyBy('key');

                $persons = DB::table("person")->where('person_type','=','customer')->get();
                $sales = Sale::all();
                $products = DB::table("product as p")
                            ->join("income_detail as ide","ide.product_id","=","p.id")
                            ->select(DB::raw('CONCAT(p.code," ",p.name) as article'),'p.stock','p.id',DB::raw('avg(ide.sale_price) as average'))
                            ->where('p.status','=',1)
                            ->where('p.stock','>','0')
                            ->groupBy('article','p.id','p.stock')
                            ->get();
                        return view('sale.sale.create',[
                        'persons'=>$persons,
                        'products'=>$products,
                        'payment_methods'=>explode(",",$payment_methods->value),
                        'payment_forms'=>explode(",",$payment_forms->value),
                        'logo'=>$logo,
                        'company'=>$company
                        ]
                        );      
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
    }

    /**
     * Store a newly createds resource in storage.
     */
    public function store(Request $request)
    {
        try{
            if(isset(auth()->user()->id)){
            $cash_opened = DB::table("cash_opening as co")
                ->where("co.users_id","=",auth()->user()->id)
                ->where("co.status","=",'open')
                ->first();
            if(!$cash_opened){
                return response()->json(
                ["success"=>false,"message"=>"No has iniciado apertura de caja"],401);
            }

                DB::beginTransaction();
                $sale = new Sale();
                $sale->customer_id = explode("-",$request->post('customer_id'))[0];
                $sale->tax = 16;
                $sale->status = 1;
                $sale->cash_opening_id = $cash_opened->id;

                $sale->payment_form = $request->post('payment_form');

                $sale->change = floatval($request->post('totalChangeHidden'));
                $sale->sale_total = floatval($request->post('sale_total'));
                $sale->users_id = auth()->user()->id;
                $sale->save();
                
                $income_details = $request->post('income_detail_id');
                $quantities = $request->post('quantity');
                $discounts= $request->post('discount');
                $sale_prices = $request->post('sale_price');
                
                $cont = 0; 

                while($cont < count($income_details)){
                    $detail = new SaleDetail();
                    $detail->sale_id = $sale->id;
                    $detail->income_detail_id = $income_details[$cont];
                    $detail->quantity = $quantities[$cont];
                    $detail->discount = $discounts[$cont];
                    $detail->sale_price = $sale_prices[$cont];
                    $detail->save();
                    $cont = $cont + 1;
                }

                $paymemts = json_decode($request->post("methods"));
                foreach($paymemts as $pay){
                    $payment = new Payment();
                    $payment->method = $pay->method;
                    $payment->sale_id = $sale->id;
                    $payment->value = $pay->value;
                    $payment->status = 1;
                    $payment->save();
                }

                DB::commit();

                $sale = DB::table("sale as s")
                ->join("person as p","s.customer_id","=","p.id")
                ->join("users as u","u.id","=","s.users_id")
                ->select("s.id","s.change","s.updated_at","p.name as customer_name","p.address as customer_address","p.phone as customer_phone","p.email as customer_email","p.document_type","p.document_number","s.tax","s.sale_total","u.name as user_name","s.payment_form")
                ->where("s.id","=",$sale->id)
                ->first();

                $details = DB::table("sale_detail as d")
                ->join("income_detail_historical as ide","ide.income_detail_id","=","d.income_detail_id")
                ->join("product as pro","pro.id","=","ide.product_id")
                ->select("pro.name as article","pro.concentration","pro.presentation","ide.form_sale","d.quantity","d.discount","d.sale_price")
                ->where("d.sale_id","=",$sale->id)
                ->get();

                $form_payment = DB::table("payment as p")
                    ->select("p.sale_id","p.method","p.value","p.status")
                    ->where("p.sale_id","=",$sale->id)
                    ->get();

                return response()->json(
                    ["success"=>true,"message"=>"Venta efectuada con éxito",
                    "info_sale"=>$sale,
                    "detail_sale"=>$details,
                    "form_payment"=>$form_payment
                    ],201);
            }else{
                return response()->json(
                ["success"=>false,"message"=>"Se debe iniciar sesión"],401);
            } 
        }catch(Exception $e){
            DB::rollBack();
            return response()->json(["success"=>false,"message"=>"Error:".$e],501);
        }
    }

    function getDataReceipt(string $id){
        $sale = DB::table("sale as s")
                 ->join("person as p","s.customer_id","=","p.id")
                 ->join("users as u","u.id","=","s.users.id")
                 ->select("s.id","s.updated_at","p.name","p.address","p.phone","p.email","s.tax","s.sale_total","u.name as user_name")
                 ->where("s.id","=",$id)
                 ->first();

        $details = DB::table("sale_detail as d")
                ->join("income_detail_historical as ide","ide.income_detail_id","=","d.income_detail_id")
                ->join("product as pro","pro.product_id","=","ide.product_id")
                ->select("pro.name as article","pro.concentration","pro.presentation","ide.form_sale","d.quantity","d.discount","d.sale_price")
                ->where("d.sale_id","=",$id)
                ->get();

        return response()->json(["sale"=>$sale,"details"=>$details]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,$id)
    {
        $sale_id = $id;
        $sale = DB::table("sale as s")
            ->join("person as p", "s.customer_id", "=", "p.id")
            ->select(
                "s.id",
                "s.change",
                "s.created_at",
                "p.name as customer_name",
                "p.address as customer_address",
                "p.phone as customer_phone",
                "p.email as customer_email",
                "p.document_type",
                "p.document_number",
                "s.tax",
                "s.sale_total",
                "s.payment_form"
            )
            ->where("s.id", "=", $sale_id)
            ->first();

        $details = DB::table("sale_detail as d")
            ->join("income_detail_historical as ide", "ide.income_detail_id", "=", "d.income_detail_id")
            ->join("product as pro", "pro.id", "=", "ide.product_id")
            ->select(
                "pro.name as article",
                "pro.concentration",
                "pro.presentation",
                "pro.laboratory",
                "ide.form_sale",
                "d.quantity",
                "d.discount",
                "ide.sale_price",
                "ide.income_detail_id"
            )
            ->where("d.sale_id", "=", $sale_id)
            ->get();

        $total = DB::table('sale_detail as sd')
                ->select(DB::raw('SUM(((sale_price * quantity) - discount)) as sale_total'))
                ->where("sd.sale_id", "=", $sale_id)
                ->first();
        
        $return_sales = DB::table("return_sale as rs")
                        ->join("income_detail_historical as ide","ide.income_detail_id","=","rs.income_detail_id")
                        ->join("product as pro","pro.id","=","ide.product_id")
                        ->join("users as us","us.id","=","rs.users_id")
                        ->select("rs.quantity","rs.description","us.name as user_name","pro.name as article","pro.concentration","pro.presentation","pro.laboratory","ide.form_sale","rs.created_at","rs.return_total")
                        ->where("rs.sale_id","=",$sale_id)
                        ->get();

        $return_total = DB::table("return_sale as rs")
                        ->select(DB::raw("SUM(rs.return_total) as sum_return_total" ))
                        ->where("rs.sale_id","=",$sale_id)
                        ->first();
                        
        
        return view('sale.sale.show', [
            'sale' => $sale,
            'details' => $details,
            'total'=>$total,
            'return_sales'=>$return_sales,
            'sum_return_total'=>$return_total->sum_return_total
        ]);
    }

    public function return_sale(Request $request){
        try{
            if(isset(auth()->user()->id)){
                DB::beginTransaction();
                $sale_id = $request->post("sale_id");
                $income_detail_id = $request->post("income_detail_id");
                $quantity_return = $request->post("quantity_return");
                $description_return = $request->post("description_return");
                $income_detail_id_found = IncomeDetail::find($income_detail_id);
                if($income_detail_id_found){
                    $income_detail_id_found->quantity = $income_detail_id_found->quantity + $quantity_return;
                    $income_detail_id_found->save();
                }else{
                    $income_detail_id_found = IncomeDetailHistorical::where('income_detail_id',$income_detail_id)->first();
                    $detail = new IncomeDetail();
                    $detail->income_id = $income_detail_id_found->income_id;
                    $detail->product_id = $income_detail_id_found->product_id;
                    $detail->quantity = $income_detail_id_found->quantity;
                    $detail->purchase_price = $income_detail_id_found->purchase_price;
                    $detail->sale_price = $income_detail_id_found->sale_price;
                    $detail->form_sale = $income_detail_id_found->form_sale;
                    $detail->expiration_date = $income_detail_id_found->expiration_date;
                    $detail->save();
                }
                $sale_detail = SaleDetail::where('income_detail_id',$income_detail_id)
                                ->where('sale_id',$sale_id)
                                ->first();
                if($sale_detail){
                    $descount_unit = $sale_detail->discount/$sale_detail->quantity;
                    $sale_detail->discount = $sale_detail->discount - ($descount_unit * $quantity_return);
                    $sale_detail->quantity =  $sale_detail->quantity - $quantity_return;
                    $sale_detail->save();
                    $return_total = ($sale_detail->sale_price * $quantity_return)-$descount_unit;
                    

                    $return_sale = new ReturnSale();
                    $return_sale->income_detail_id = $income_detail_id;
                    $return_sale->sale_id = $sale_id;
                    $return_sale->description = $description_return?$description_return:"";
                    $return_sale->quantity = $quantity_return;
                    $return_sale->status = 1;
                    $return_sale->return_total = $return_total;
                    $return_sale->users_id = auth()->user()->id;
                    $return_sale->save();
                }
                $total = DB::table('sale_detail as sd')
                ->select(DB::raw('SUM(((sale_price * quantity) - discount)) as sale_total'))
                ->where("sd.sale_id", "=", $sale_id)
                ->first();
                $sale = Sale::where('id',$sale_id)
                        ->first();
                $sale->sale_total = $total->sale_total; 
                $sale->save();

                DB::commit();
                return response()->json(
                    ["success"=>true,"message"=>"Devolución exitosa"
                ],201);

            }else{
                return response()->json(
                ["success"=>false,"message"=>"La sesión a caducado, reinicia la sesión"
                ],501);
            }
        }catch(Exception $err){
            DB::rollBack();
            return response()->json(
            ["success"=>false,"message"=>$err->getMessage()
            ],501);
        }
    }

    public function receipt($sale_id){
        $sale = DB::table("sale as s")
        ->join("person as p","s.customer_id","=","p.id")
        ->join("users as u","u.id","=","s.users_id")
        ->select("s.id","s.change","s.updated_at","p.name as customer_name","p.address as customer_address","p.phone as customer_phone","p.email as customer_email","p.document_type","p.document_number","s.tax","s.sale_total","u.name as user_name","s.payment_form")
        ->where("s.id","=",$sale_id)
        ->first();

        $details = DB::table("sale_detail as d")
        ->join("income_detail_historical as ide","ide.income_detail_id","=","d.income_detail_id")
        ->join("product as pro","pro.id","=","ide.product_id")
        ->select("pro.name as article","pro.concentration","pro.presentation","ide.form_sale","d.quantity","d.discount","d.sale_price")
        ->where("d.sale_id","=",$sale_id)
        ->get();

        $form_payment = DB::table("payment as p")
            ->select("p.sale_id","p.method","p.value","p.status")
            ->where("p.sale_id","=",$sale_id)
            ->get();

        return response()->json(
            ["success"=>true,"message"=>"Venta efectuada con éxito",
            "info_sale"=>$sale,
            "detail_sale"=>$details,
            "form_payment"=>$form_payment
            ],201);
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
        $income = Sale::findOrFail($id);
        $income->status = "C";
        $income->update();
        return Redirect::to("sale/sale");
    }
}
