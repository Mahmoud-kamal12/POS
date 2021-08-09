<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProducts extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasPermission('products_create'))
            return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        foreach (config('translatable.locales') as $locale ){
            $rules += [$locale . '.name' => 'required|unique:product_translations,name'];
            $rules += [$locale . '.description' => 'required|unique:product_translations,name'];
        }
        $rules += ['category_id' => 'required|numeric'];
        $rules += ['purchase_price' => 'required|numeric'];
        $rules += ['sale_price' => 'required|numeric'];
        $rules += ['stock' => 'required|numeric'];
        return $rules;
    }
}
