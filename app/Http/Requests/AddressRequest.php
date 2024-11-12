<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|max:255',
            'details' => 'required|string|max:500',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255'
        ];
    }

    public function attributes()
    {
        return [
            'type' => __('type'),
            'details' => __('details'),
            'first_name' => __('first_name'),
            'last_name' => __('last_name'),
            'postal_code' => __('postal_code'),
            'street' => __('street'),
            'country' => __('country')
        ];

    }
}
