<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRateRequest extends FormRequest
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
            'product_id' => 'required|exists:App\Models\Product,id',
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
            'file' => 'nullable|file|image:jpg,png,jpeg',
        ];
    }

    public function attributes()
    {
        return [
            'product_id' => __('product_id'),
            'rate' => __('rate'),
            'comment' => __('comment'),
            'file' => __('file')
        ];

    }
}
