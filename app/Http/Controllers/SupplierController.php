<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Http\Requests\SupplierFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $suppliers = DB::table("person")
                ->where('name', 'like', '%' . $query . '%')
                ->where('person_type','=','supplier')
                ->where('status', "=", 1)
                ->orderBy('name', 'asc')
                ->paginate(7);

            return view('purchase.supplier.index', ['suppliers' => $suppliers, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("purchase.supplier.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierFormRequest $request)
    {
        $supplier = new Supplier();
        $supplier->person_type = 'supplier';
        $supplier->name = $request->post('name');
        $supplier->document_type = $request->post('document_type');
        $supplier->document_number = $request->post('document_number');
        $supplier->address = $request->post('address');
        $supplier->phone = $request->post('phone');
        $supplier->email = $request->post('email');
        $supplier->status = 1;
        $supplier->save();
        return Redirect::to('purchase/supplier');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('purchase.supplier.show', ['supplier' => Supplier::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("purchase.supplier.edit", ["supplier" => Supplier::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierFormRequest $request, string $id)
    {
        $supplier = Supplier::findOrFail($id); 
        $supplier->person_type = 'supplier';
        $supplier->name = $request->get('name');
        $supplier->document_type = $request->get('document_type');
        $supplier->document_number = $request->get('document_number');
        $supplier->address = $request->get('address');
        $supplier->phone = $request->get('phone');
        $supplier->email = $request->get('email');
        $supplier->update();
        return Redirect::to("purchase/supplier");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->status = 0;
        $supplier->update();
        return Redirect::to("purchase/supplier")->with("success","Proveedor eliminado correctamente");
    }
}
