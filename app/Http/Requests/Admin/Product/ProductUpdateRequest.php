<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProductUpdateRequest extends FormRequest
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
            'sku'            => ['required', 'string'],
            'category_id'    => ['required', 'numeric'],
            'brand_id'       => ['nullable', 'numeric'],
            'price'          => ['required', 'numeric', decimal_validation()],
            'sale_price'          => ['required','numeric', decimal_validation()],
            'discount_percentage_selling_price'          => ['required','numeric', decimal_validation()],
            'packaging_shipping_fees'          => ['required','numeric', decimal_validation()],
            'management_fees'          => ['required','numeric', decimal_validation()],
            'profit_percentage'          => ['required','numeric', decimal_validation()],
            'tax_percentage'          => ['required','numeric', decimal_validation()],
            'commercial_percentage'          => ['required','numeric', decimal_validation()],
            'manual_price_adjustment'          => ['required','numeric', decimal_validation()],
            'final_price'          => ['required','numeric', decimal_validation()],
            'final_selling_price'          => ['required','numeric', decimal_validation()],
            'quantity'       => ['required', 'numeric'],
            'shipping'       => ['nullable'],
            'length'         => ['nullable', 'numeric'],
            'width'          => ['nullable', 'numeric'],
            'height'         => ['nullable', 'numeric'],
            'dimension_unit' => ['nullable', 'string'],
            'weight'         => ['nullable', 'numeric'],
            'weight_unit'    => ['nullable', 'string'],
            'attributes_ids' => ['nullable'],
            'discount_price' => ['nullable', 'numeric'],
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
