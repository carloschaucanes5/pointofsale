<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct() {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchText = trim($request->get("searchText"));
        $products = DB::table('product as p')
                    ->join('category as c','p.category_id','=','c.id')
                    ->select('p.id','p.code','p.name','p.stock','p.description','p.image','p.status','c.category')
                    ->where('p.name','like','%'.$searchText.'%')
                    ->orwhere('p.code','like','%'.$searchText.'%')
                    ->orderBy('p.id')
                    ->paginate(10);
        return view('store.product.index',compact('products','searchText'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DB::table("category")
                ->where('status', "=", 1)
                ->orderBy('id', 'desc')
                ->get();
                
        return view("store.product.create",['category' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
    {
        $product = new Product();
        $product->category_id = $request->input('category_id');
        $product->code = $request->input('code');
        $product->description = $request->input('description');
        $product->stock = $request->input('stock');
        $product->name = $request->input('name');
        $product->status = 1;
        if($request->hashFile('image')){
            $image = $request->file("image");
            $nameimage = Str::slug($request->name).".".$image->guessExtension();
            $route = public_path('/images/products/');
            copy($image->getRealPath(),$route.$nameimage);
            $product->image = $nameimage;
        }
        $product->save();
        return redirect()->route("product.index");
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
        $product = Product::findOrFail($id);
        $categories = DB::table("category")
            ->where('status', "=", 1)
            ->orderBy('id', 'desc')
            ->get();
        return view('store.product.edit',['product'=>$product,'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->code = $request->input('code');
        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->stock= $request->input('stock');
        if($request->hashFile('image')){
            $image = $request->file("image");
            $nameimage = Str::slug($request->name).".".$image->guessExtension();
            $route = public_path('/images/products/');
            copy($image->getRealPath(),$route.$nameimage);
            $product->image = $nameimage;

        }
        $product->update();
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
