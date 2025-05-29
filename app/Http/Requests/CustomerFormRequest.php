<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerFormRequest extends FormRequest
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

        return [
            'name' => 'required|max:100',
            'document_type'=>'required|max:20',
            'document_number'=>'required:max:15',
            'address'=>'required|max:70',
            'phone'=>'required|max:15',
            'email'=>'required|max:50',
        ];
    }
}

