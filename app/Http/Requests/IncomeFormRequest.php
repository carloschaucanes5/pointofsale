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
            'voucher_id'=>'required|integer|exists:voucher,id',
            'products' => ['required', 'array'],
            'products.*' => ['nullable', 'integer', 'exists:product,id'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'numeric', 'min:1'],
            'purchase_prices' => ['required', 'array'],
            'purchase_prices.*' => ['nullable', 'numeric', 'min:0'],
            'sale_prices' => ['required', 'array'],
            'sale_prices.*' => ['nullable', 'numeric', 'min:0']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
        $products = $this->input('products', []);
        $quantities = $this->input('quantities', []);
        $purchasePrices = $this->input('purchase_prices', []);
        $salePrices = $this->input('sale_prices', []);

        $hasAtLeastOne = false;

        for ($i = 0; $i < count($products); $i++) {
            if (
                !empty($products[$i]) &&
                !empty($quantities[$i]) &&
                !empty($purchasePrices[$i]) &&
                !empty($salePrices[$i])
            ) {
                $hasAtLeastOne = true;
                break;
            }
        }
        if (!$hasAtLeastOne){
            $validator->errors()->add('products', 'Debes ingresar al menos un producto con todos sus campos (cantidad, precio de compra y venta).');
         }
        });
    }
}
