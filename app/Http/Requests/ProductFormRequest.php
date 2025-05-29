<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'required|max:100',
            'description' => 'max:256',
            'code'=>'required|max:256|unique:product,code',
            'image'=>'file|mimes:jpg,png|max:2048',
            'stock'=>'required|integer',
            'presentation'=>'required',
            'concentration'=>'required'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // 'code' único, ignorando el ID del producto actual
            $rules['code'] = 'required|max:256|unique:product,code,' . $this->route('product');
        }

        return $rules;
    }

    public function hashFile($fileInputName)
    {
        if ($this->hasFile($fileInputName)) {
            $file = $this->file($fileInputName);
            return hash_file('sha256', $file->getRealPath());
        }
        return null;
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre del producto no puede tener más de 100 caracteres.',
            
            'description.max' => 'La descripción no puede tener más de 256 caracteres.',
            
            'code.max' => 'El código no puede tener más de 256 caracteres.',
            'code.required' => 'El código  de barras es obligatorio',
            
            'image.file' => 'La imagen debe ser un archivo válido.',
            'image.mimes' => 'La imagen debe ser de tipo jpg, png o pdf.',
            'image.max' => 'El tamaño de la imagen no puede ser mayor a 2 MB.',
            
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            
            'presentation.required' => 'La presentación es obligatoria.',
            
            'concentration.required' => 'La concentración es obligatoria.',
        ];
    }
}
