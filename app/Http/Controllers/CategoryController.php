<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryFormRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {}

    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $categories = DB::table("category")
                ->where('category', 'like', '%' . $query . '%')
                ->where('status', "=", 1)
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('store.category.index', ['category' => $categories, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("store.category.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request)
    {
        $category = new Category();
        $category->category = $request->get('category');
        $category->description = $request->get('description');
        $category->status = 1;
        $category->save();
        return Redirect::to('store/category');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('store.category.show', ['category' => Category::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("store.category.edit", ["category" => Category::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryFormRequest $request, $id)
    {
        $category = Category::findOrFail($id); 
        $category->category = $request->get('category');
        $category->description = $request->get('description');
        $category->update();
        return Redirect::to("store/category");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->status = 0;
        $category->update();
        return Redirect::to("store/category")->with("success","Categoria eliminada correctamente");
    }
}
