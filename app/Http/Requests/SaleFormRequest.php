<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleFormRequest extends FormRequest
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
            'customer_id'=>'required',
            'voucher_type'=>'required|max:20',
            'voucher_number'=>'max:8',
            'product_id' => 'required',
            'products' => ['required', 'array'],
            'products.*' => ['nullable', 'integer', 'exists:product,id'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'numeric', 'min:1'],
            'sale_prices' => ['required', 'array'],
            'sale_prices.*' => ['nullable', 'numeric', 'min:0']
        ];
    }
}

/*


*/