<?php

namespace App\Http\Requests\Product;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|min:2|max:20',
            'description' => 'required|string|min:5|max:255',
            'slug' => 'required|string',
            'price' => 'required|numeric',
            'properties' => 'required|array',
        ];

        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|unique:products,slug,except,id';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name)
        ]);
    }
}
