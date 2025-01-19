<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CustomerFormRequest;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class CustomerController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $customers = DB::table("person")
                ->where('name', 'like', '%' . $query . '%')
                ->where('status', "=", 1)
                ->orderBy('name', 'asc')
                ->paginate(7);

            return view('sale.customer.index', ['customers' => $customers, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("sale.customer.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerFormRequest $request)
    {
        $customer = new Customer();
        $customer->person_type = $request->get('person_type');
        $customer->name = $request->get('name');
        $customer->document_type = $request->get('document_type');
        $customer->document_number = $request->get('document_number');
        $customer->address = $request->get('address');
        $customer->phone = $request->get('phone');
        $customer->email = $request->get('email');
        $customer->status = 1;
        $category->save();
        return Redirect::to('sale/customer');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('sale.customer.show', ['customer' => Customer::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("sale.customer.edit", ["customer" => Category::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerFormRequest $request, $id)
    {
        $customer = Customer::findOrFail($id); 
        $customer->person_type = $request->get('person_type');
        $customer->name = $request->get('name');
        $customer->document_type = $request->get('document_type');
        $customer->document_number = $request->get('document_number');
        $customer->address = $request->get('address');
        $customer->phone = $request->get('phone');
        $customer->email = $request->get('email');
        $customer->update();
        return Redirect::to("sale/customer");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Category::findOrFail($id);
        $customer->status = 0;
        $customer->update();
        return Redirect::to("sale/customer")->with("success","Cliente eliminado correctamente");
    }
}
