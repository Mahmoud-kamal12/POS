<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProducts extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasPermission('products_update'))
            return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach (config('translatable.locales') as $locale ){
            $rules += [$locale . '.name' => ['required' , Rule::unique('product_translations', 'name')->ignore($this->product->id , 'product_id')]];
            $rules += [$locale . '.description' => 'required|unique:product_translations,name,'.$this->product->id];
        }
        $rules += ['category_id' => 'required|numeric'];
        $rules += ['purchase_price' => 'required|numeric'];
        $rules += ['sale_price' => 'required|numeric'];
        $rules += ['stock' => 'required|numeric'];
        return $rules;
    }
}
