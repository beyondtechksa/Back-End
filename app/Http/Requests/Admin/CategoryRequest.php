<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'menu_name' => 'required|array',
            'menu_name.en' => 'required|string|max:255',
            'menu_name.ar' => 'required|string|max:255',
            'category_id' => 'nullable|numeric|exists:categories,id',
            'desc' => 'nullable|array',
            'desc.en' => 'nullable|string|max:255',
            'desc.ar' => 'nullable|string|max:255',
            'status' => 'nullable',
            'image' => 'nullable|string|max:255',
        ];
    }
}
