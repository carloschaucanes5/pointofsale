<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SaleFormRequest;
use App\Models\Payment;
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
        if($request){
            $query = trim($request->get('searchText'));
            $sales = DB::table("sale as sal")
                       ->join('person as pe','pe.id','=','sal.customer_id')
                       ->join('sale_detail as sde','sde.sale_id','=','sal.id')
                       ->select('sal.id','sal.created_at','pe.name','sal.tax','sal.status','sal.sale_total')
                       ->groupBy('sal.id','sal.created_at','pe.name','sal.tax','sal.status','sal.sale_total')
                       ->orderBy('sal.id','desc')
                       ->paginate(15);
            return view('sale.sale.index',['sales'=>$sales,'texto'=>$query]);
        }
    }

    /**
     * Buscar el producto en inventario sea por nombre o codigo de barras
     */

     public function search_product(string $textSearch){
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
     }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $payment_methods =DB::table('config')
                          ->where("key","=","payment_methods")
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
        'logo'=>$logo,
        'company'=>$company
        ]
    );            
    }

    /**
     * Store a newly createds resource in storage.
     */
    public function store(Request $request)
    {
        try{
            if(auth()->user()->id){
                 DB::beginTransaction();
                $sale = new Sale();
                $sale->customer_id = explode("-",$request->post('customer_id'))[0];
                $sale->tax = 16;
                $sale->status = 1;
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
                 ->select("s.id","s.change","s.updated_at","p.name as customer_name","p.address as customer_address","p.phone as customer_phone","p.email as customer_email","p.document_type","p.document_number","s.tax","s.sale_total","u.name as user_name")
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
    public function show(string $id)
    {
        

        $sale = DB::table("sale as s")
                 ->join("person as p","s.customer_id","=","p.id")
                 ->join("sale_detail as sde","s.id","=","sde.sale_id")
                 ->select("s.id","s.created_at","s.updated_at","p.name","s.voucher_type","s.voucher_number","s.tax","s.status","s.sale_total")
                 ->where("s.id","=",$id)
                 ->first();

        $details = DB::table("sale_detail as d")
                ->join("product as pro","pro.id","=","d.product_id")
                ->select("pro.name as article","d.quantity","d.discount","d.sale_price")
                ->where("d.sale_id","=",$id)
                ->get();

        return view("sale.sale.show",["sale"=>$sale,"details"=>$details]);

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
