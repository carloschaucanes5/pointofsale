<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeFormRequest extends FormRequest
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
            'supplier_id'=>'required',
            'voucher_type'=>'required|max:20',
            'voucher_number'=>'max:7',
            'product_id'=>'required:max:20',
            'quantity'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required'

        ];
    }
}
