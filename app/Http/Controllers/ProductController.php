<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $texto = $request->get("texto");
        $products = DB::table('product as p')
                    ->join('category as c','p.category_id','=','c.id')
                    ->select('p.id,p.code,p.name,p.stock,p.description,p.image,p.status,c.category')
                    ->where('p.name','like','%'.$texto.'%')
                    ->orwhere('p.code','like','%'.$texto.'%')
                    ->orderBy('p.id')
                    ->paginate(10);
        return view('store.product.index',compact('productos','texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
