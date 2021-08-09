<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\This;

class UpadteCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->hasPermission('categories_update'))
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
            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations', 'name')->ignore($this->category->id , 'category_id')]];
        }
        return $rules;
    }
}
