<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SaleFormRequest;
use Exception;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\Sale;
use App\Models\SaleDetail;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if($request){
            $query = trim($request->get('searchText'));
            $sales = DB::table("sale as sal")
                       ->join('person as pe','pe.id','=','sal.customer_id')
                       ->join('sale_detail as sde','sde.sale_id','=','sal.id')
                       ->select('sal.id','sal.created_at','pe.name','sal.voucher_type','sal.voucher_number','sal.tax','sal.status','sal.sale_total')
                       ->where('sal.voucher_number','like','%'.$query.'%')
                       ->groupBy('sal.id','sal.created_at','pe.name','sal.voucher_type','sal.voucher_number','sal.tax','sal.status','sal.sale_total')
                       ->orderBy('sal.id','desc')
                       ->paginate(15);
            return view('sale.sale.index',['sales'=>$sales,'texto'=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $persons = DB::table("person")->where('person_type','=','customer')->get();
        $sales = Sale::all();
        $products = DB::table("product as p")
                    ->join("income_detail as ide","ide.product_id","=","p.id")
                    ->select(DB::raw('CONCAT(p.code," ",p.name) as article'),'p.stock','p.id',DB::raw('avg(ide.sale_price) as average'))
                    ->where('p.status','=',1)
                    ->where('p.stock','>','0')
                    ->groupBy('article','p.id','p.stock')
                    ->get();
        return view('sale.sale.create',['persons'=>$persons,'products'=>$products]);            
    }

    /**
     * Store a newly createds resource in storage.
     */
    public function store(SaleFormRequest $request)
    {

        try{
            DB::beginTransaction();
            $sale = new Sale();
            $sale->customer_id = $request->post('customer_id');
            $sale->voucher_type = $request->post('voucher_type');
            $sale->voucher_number = $request->post('voucher_number');
            $sale->tax = 16;
            $sale->status = 1;
            $sale->sale_total = $request->post('sale_total');
            $sale->save();
            
            $products = $request->post('products');
            $quantities = $request->post('quantities');
            $discounts= $request->post('discounts');
            $sale_prices = $request->post('sale_prices');
            
            $cont = 0; 
            while($cont < count($products)){
                $detail = new SaleDetail();
                $detail->sale_id = $sale->id;
                $detail->product_id = $products[$cont];
                $detail->quantity = $quantities[$cont];
                $detail->discount = $discounts[$cont];
                $detail->sale_price = $sale_prices[$cont];
                $detail->save();
                $cont = $cont + 1;
            }
            DB::commit();
            
        }catch(Exception $e){
            DB::rollBack();
        }
        return Redirect::to("sale/sale");
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
