<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Income;
use App\Models\Product;
use App\Models\IncomeDetail;
use App\Models\Voucher;
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
            $query = trim($request->get('searchText'));
            $incomes = DB::table("income as i")
                ->join("voucher as v", "i.voucher_id", "=", "v.id")
                ->join("person as p", "v.supplier_id", "=", "p.id")
                ->join("users as u", "i.users_id", "=", "u.id")
                ->select(
                    'i.id',
                    'i.created_at',
                    'i.updated_at',
                    'p.name as supplier_name',
                    'v.id as voucher_id',
                    'v.voucher_number',
                    DB::raw('SUM(ide.quantity * ide.purchase_price) as total'),
                    'u.name as user_name'
                )
                ->join('income_detail as ide', 'i.id', '=', 'ide.income_id')
                ->where('i.status', '=', 1)
                ->where(function($q) use ($query) {
                    $q->where('v.voucher_number', 'like', '%' . $query . '%')
                      ->orWhere('p.name', 'like', '%' . $query . '%');
                })
                ->groupBy('i.id', 'i.created_at', 'i.updated_at', 'p.name', 'v.voucher_number', 'v.id', 'u.name')
                ->orderBy('i.id', 'desc')
                ->paginate(5);

            return view('purchase.income.index',['incomes'=>$incomes,'texto'=>$query]);
        }
    }

    public function search_product($codeOrName){
        $products = DB::table('product')
            ->where(function($query) use ($codeOrName) {
                $query->where('name', 'like', '%' . $codeOrName . '%')
                      ->orWhere('code', 'like', '%' . $codeOrName . '%');
            })
            ->where('status', '=', 1)
            ->select('id', 'code', 'name', 'stock','concentration', 'presentation')
            ->get();

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
            'voucher.status_payment',
            'voucher.updated_at',
            'users.name as user_name'
        )
        ->where('voucher.status', '=', 1)
        ->orderBy('voucher.id', 'desc')
        ->get();

        $forms = DB::table("formsale")->get();
        $persons = DB::table("person")->where('person_type','=','supplier')->get();
        $incomes = Income::all();
        $products = DB::table("product as p")
                    ->select(DB::raw('CONCAT(p.code," ",p.name) as article'),'p.stock','p.id')
                    ->where('p.status','=',1)
                    ->get();
        return view('purchase.income.create',['persons'=>$persons,'incomes'=>$incomes,'products'=>$products, 'forms'=>$forms, 'vouchers'=>$vouchers]);            
    }

    /**
     * Store a newly createds resource in storage.
     */
    public function store(IncomeFormRequest $request)
    {
        try{
            DB::beginTransaction();
            $income = new Income();
            $income->voucher_id = $request->post('voucher_id');
            $income->users_id = auth()->user()->id; // Assuming you want to set the current authenticated user
            $income->tax = 0;
            $income->status = 1;
            $income->save();
            
            $products = $request->post('products');
            $quantities = $request->post('quantities');
            $purchase_prices = $request->post('purchase_prices');
            $sale_prices = $request->post('sale_prices');
            $forms_sale = $request->post('forms_sale');
            $expiration_dates = $request->post('expiration_dates');
            
            $cont = 0;
            while($cont < count($products)){
                $detail = new IncomeDetail();
                $detail->income_id = $income->id;
                $detail->product_id = $products[$cont];
                $detail->quantity = $quantities[$cont];
                $detail->purchase_price = $purchase_prices[$cont];
                $detail->sale_price = $sale_prices[$cont];
                $detail->form_sale = $forms_sale[$cont];
                $detail->expiration_date = Carbon::parse($expiration_dates[$cont])->format('Y-m-d');
                $detail->save();
                $cont = $cont + 1;
            }
                // Update the voucher status to 'A' (active)
                $voucher = Voucher::findOrFail($request->post('voucher_id'));
                $voucher->status = 0;
                $voucher->save();
    
                // Update the product stock
                foreach ($products as $index => $productId) {
                    $product = Product::findOrFail($productId);
                    $product->stock += $quantities[$index];
                    $product->save();
                }
            DB::commit();
             return response()->json([
            'message' => 'Factura registrada correctamente.',
            'success' => true
        ]);
            
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $income = DB::table("income as i")
                 ->join("voucher as v","v.id","=","i.voucher_id")
                 ->join("person as p","p.id","=","v.supplier_id")
                 ->select("i.id","i.created_at","i.updated_at","p.name","i.tax","i.status","v.voucher_number","v.total")
                ->selectSub(function($query){
                    $query->from("users")
                        ->select("name")
                        ->whereColumn("users.id","i.users_id")
                        ->limit(1);
                        },"users_name")
                 ->where("i.id","=",$id)
                 ->orderBy("i.id","desc")
                 ->first();

        $details = DB::table("income_detail as d")
                ->join("product as pro","pro.id","=","d.product_id")
                ->select("pro.name as article","d.quantity","d.purchase_price","d.sale_price","d.form_sale","d.expiration_date","pro.concentration","pro.presentation")
                ->where("d.income_id","=",$id)
                ->get();

        return view("purchase.income.show",["income"=>$income,"details"=>$details]);

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
        $income = Income::findOrFail($id);
        $income->status = "C";
        $income->update();
        return Redirect::to("purchase/income");
    }

    public function view_voucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        return response()->json([
            'success' => true,
            'voucher_number' => $voucher->voucher_number,
            'photo_url' => asset($voucher->photo),
        ]);
    }
}
