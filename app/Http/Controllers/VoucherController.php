<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherFormRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct() {}

    public function index(Request $request)
    {
        if ($request) {
            $query = trim($request->get('searchText'));
            $vouchers = DB::table("voucher")
                ->where('voucher_number', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);

            return view('purchase.voucher.index', ['vouchers' => $vouchers, 'searchText' => $query]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("purchase.voucher.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherFormRequest $request)
    { 
        try{
        DB::beginTransaction();
        $voucher = new Voucher();
        $voucher->voucher_number = $request->get('voucher_number');
        $voucher->total = $request->get('total');
        $voucher->description = $request->get('description');
        $voucher->status = 1;
        $voucher->save();
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photo = $request->file('photo');
            $folder = public_path('images/purchase/voucher');
            // Crea la carpeta si no existe
            if (!file_exists($folder)) {
                mkdir($folder, 0775, true);
            }
            $id = $voucher->id?$voucher->id:$voucher->voucher_number;
            $name = $id . '.' . $photo->getClientOriginalExtension();
            // Mueve el archivo al directorio público
            $movedFile = $photo->move($folder, $name);
            if($movedFile && file_exists($movedFile->getPathname())){
                $voucher->photo = 'images/purchase/voucher/' . $id;
                $voucher->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Factura creada correctamente',
                    'voucher' => $voucher
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe la ruta de la imagen o no se ha podido mover el archivo.'
                ], 500);
              
            }       
        }
        else
        {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'No se ha subido la imagen correctamente. Por favor, inténtelo de nuevo.'
            ], 500);
            
        }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la factura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('purchase.voucher.show', ['voucher' => Voucher::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view("purchase.voucher.edit", ["voucher" => Voucher::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherFormRequest $request, $id)
    {
        $voucher = Voucher::findOrFail($id); 
        $voucher->total = $request->get('total');
        $voucher->description = $request->get('description');
        $voucher->voucher_number = $request->get('voucher_number');
        $voucher->status = 1;
        $voucher->update();
        return Redirect::to("purchase/voucher");
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->status = 0;
        $voucher->update();
        return Redirect::to("purchase/voucher");
    }
}
