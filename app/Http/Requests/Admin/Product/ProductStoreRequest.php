<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductStoreRequest extends FormRequest
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
            'name_en'           => ['required', 'string','max:255'],
            'name_ar'           => ['required', 'string','max:255'],
            'desc_en'           => ['required', 'string'],
            'desc_ar'           => ['required', 'string'],
            // 'sku'            => ['required', 'string'],
            'pricing_type'            => ['nullable', 'string'],
            'category_id'    => ['required', 'integer'],
            'currency_id'    => ['required', 'integer'],
            'brand_id'       => ['nullable', 'integer'],
            'price'          => ['required',  decimal_validation()],
            'sale_price'          => ['required', decimal_validation()],
            'discount_percentage_selling_price'          => ['nullable','integer', decimal_validation()],
            'packaging_shipping_fees'          => ['nullable','integer', decimal_validation()],
            'management_fees'          => ['nullable','integer', decimal_validation()],
            'profit_percentage'          => ['nullable','integer', decimal_validation()],
            'tax_percentage'          => ['required','integer', decimal_validation()],
            'commercial_percentage'          => ['nullable','integer', decimal_validation()],
            'manual_price_adjustment'          => ['nullable','integer', decimal_validation()],
            'final_price'          => ['required', decimal_validation()],
            'final_selling_price'          => [ decimal_validation()],
            'quantity'       => ['required', 'integer'],
            'shipping'       => ['nullable'],
            'length'         => ['nullable', 'integer'],
            'width'          => ['nullable', 'integer'],
            'height'         => ['nullable', 'integer'],
            'dimension_unit' => ['nullable', 'string'],
            'weight'         => ['nullable', 'integer'],
            'weight_unit'    => ['nullable', 'string'],
            'attributes_ids' => ['nullable'],
            'discount_price' => ['nullable', 'integer'],
            'start_at'       => ['nullable', 'date'],
            'end_at'         => ['nullable', 'date'],
            'points'         => ['nullable', 'integer'],
            'image'          => ['required', 'string'],
            'files'          => ['nullable'],
        ];
    }

protected function failedValidation(Validator $validator)
{
    throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
}
}
