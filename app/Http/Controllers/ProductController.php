<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Laboratory;
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
                    ->select('p.id','p.code','p.name','p.stock','p.description','p.image','p.status','c.category','p.presentation','p.concentration','p.laboratory')
                    ->where('p.name','like','%'.$searchText.'%')
                    ->orwhere('p.code','like','%'.$searchText.'%')
                    ->orderBy('p.id')
                    ->paginate(5);
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

        $laboratories = DB::table("laboratory")
                        ->where('status',"=",1)
                        ->orderBy('id','desc')
                        ->get();
                
        return view("store.product.create",['category' => $categories,'laboratories'=>$laboratories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductFormRequest $request)
     {
        // Creamos una nueva instancia del producto
        
        $product = new Product();

        $laboratories = Laboratory::all();
        $customAttributes = [
            'laboratory' => 'Laboratorio',  // Aquí defines el alias que se usará en los mensajes
        ];

        $request->validate([
            'laboratory' => 'required|in:' . implode(',', $laboratories->pluck('name')->toArray())
        ], [], $customAttributes); 

        // Asignamos los valores recibidos desde el formulario
        $product->category_id = $request->input('category_id');
        $product->code = $request->input('code');
        $product->presentation = $request->input('presentation');
        $product->concentration = $request->input('concentration');
        $product->description = $request->input('description');
        $product->laboratory = $request->input('laboratory');
        $product->stock = 0;
        $product->name = $request->input('name');
        $product->status = 1;  // Asumimos que por defecto el producto está activo
        $ima = "";
        // Comprobamos si hay un archivo de imagen
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $nameimage = Str::slug($product->code) . "." . $image->guessExtension();
            $ima = $nameimage;
            $route = public_path('/images/products/');
            
            // Si el directorio no existe, lo creamos
            if (!file_exists($route)) {
                mkdir($route, 0777, true);
            }

            // Guardamos la imagen en la ruta especificada
            $image->move($route, $nameimage);
            // Guardamos el nombre de la imagen en la base de datos
        }
        $product->image = $ima; 

        // Guardamos el producto en la base de datos
        $product->save();
        
        // Redirigimos a la lista de productos
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

        $laboratories = DB::table("laboratory")
        ->where('status',"=",1)
        ->orderBy('id','desc')
        ->get();

        $product = Product::findOrFail($id);
        $categories = DB::table("category")
            ->where('status', "=", 1)
            ->orderBy('id', 'desc')
            ->get();
        return view('store.product.edit',['product'=>$product,'categories'=>$categories,'laboratories'=>$laboratories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductFormRequest $request, string $id)
    {
        $laboratories = Laboratory::all();
        $customAttributes = [
            'laboratory' => 'Laboratorio', 
        ];
    
        $request->validate([
            'laboratory' => 'required|in:' . implode(',', $laboratories->pluck('name')->toArray())
        ], [], $customAttributes); 
    
        // Encuentra el producto por ID
        $product = Product::findOrFail($id);
        
        // Actualiza los campos del producto
        $product->code = $request->input('code');
        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->stock = $request->input('stock');
        $product->presentation = $request->input('presentation');
        $product->concentration = $request->input('concentration');
        $product->laboratory = $request->input('laboratory');
    
            $ima = "";
        // Comprobamos si hay un archivo de imagen
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $nameimage = Str::slug($product->code) . "." . $image->guessExtension();
            $ima = $nameimage;
            $route = public_path('/images/products/');
            
            // Si el directorio no existe, lo creamos
            if (!file_exists($route)) {
                mkdir($route, 0777, true);
            }

            // Guardamos la imagen en la ruta especificada
            $image->move($route, $nameimage);
            $product->image = $nameimage; // Guardamos el nombre de la imagen en la base de datos
        }
        $product->image = $ima;
    
        // Guarda los cambios
        $product->update();
    
        // Redirige a la lista de productos
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->status = 0;
        $product->update();
        return Redirect::to("store/product")->with("success","Producto ha cambiado a estado inactivo");
    }
}
