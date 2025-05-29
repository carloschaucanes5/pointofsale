<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaboratoryFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|max:50|unique:laboratory,name'
        ];
    }



    public function messages(){
        return [
            'name.required'=>'El nombre del laboratorio es obligatorio',
            'name.max'=>'El nombre del laboratorio no debe exceder de los 50 caracteres',
            'name.unique'=>'El nombre del laboratorio ya se encuentra ingresado',
        ];
    }
}
