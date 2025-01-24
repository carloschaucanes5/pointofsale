<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Http\Requests\IncomeFormRequest;
use Exception;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        
    }

    public function index(Request $request)
    {
        if($request){
            $query = trim($request->get('texto'));
            $incomes = DB::table("income as inc")
                       ->join('person as pe','pe.id','=','inc.supplier_id')
                       ->join('income_detail as ide','ide.income_id','=','inc.id')
                       ->select('inc.id','inc.created_at','inc.updated_at','pe.name','inc.voucher_type','inc.voucher_number','inc.tax','inc.status',DB::raw('sum(ide.quantity * ide.purchase_price) as total'))
                       ->where('inc.voucher_number','like','%'.$query.'%')
                       ->groupBy('inc.id','inc.created_at','pe.name','inc.voucher_type','inc.voucher_number','inc.tax','inc.status')
                       ->orderBy('inc.id','desc')
                       ->paginate(15);
            return view('purchase.income.index',['incomes'=>$incomes,'text'=>$query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $persons = DB::table("person")->where('person_type','=','supplier')->get();
        $incomes = Income::all();
        $products = DB::table("product as p")
                    ->select(DB::raw('CONCAT(p.code," ",p.name) as article'),'p.stock','p.unit')
                    ->where('p.status','=',1)
                    ->get();
        return view('purchase.income.create',['persons'=>$persons,'incomes'=>$incomes,'products'=>$products]);            
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $income = new Income();
            $income->supplier_id = $request->get('supplier_id');
            $income->voucher_type = $request->get('voucher_type');
            $income->voucher_number = $request->get('voucher_number');
            $income->tax = 16;
            $income->status = 1;
            $income->save();

            $products = $request->get('products');
            $quantities = $request->get('quantities');
            $purchase_prices = $request->get('purchase_price');
            $sale_prices = $request->get('sale_price');
            
            $cont = 0;
            while($cont < count($products)){
                $detail = new IncomeDetail();
                $detail->income_id = $income->id;
                $detail->product_id = $products[$cont];
                $detail->quantity = $quantities[$cont];
                $detail->purchase_price = $purchase_prices[$cont];
                $detail->sale_price = $sale_prices[$cont];
                $detail->save();
                $cont = $cont + 1;
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
        return Redirect::to("purchase/income");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
