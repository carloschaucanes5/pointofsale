<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaboratoryFormRequest;
use App\Models\Laboratory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(LaboratoryFormRequest $request)
    {
        try {
            $laboratory = new Laboratory();
            $laboratory->name = $request->input('name');
            $laboratory->status = 1;
            if($laboratory->save()){
                return response()->json(['success' => true,'data'=>$laboratory],200);
            }
            else
            {
                return response()->json(['false' => true,'data'=>''],200);
            }
            
           

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => json_encode($e)], 500);
        }
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
