<?php

namespace App\Http\Requests\Payrol;

use Illuminate\Foundation\Http\FormRequest;

class PayrollParRequest extends FormRequest
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
        return [
            'name' => 'required',
            'defValue' => 'required',
            'categoryId' => 'required',
        ];
    }

    /**
     * Get the messgaes rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'defValue.required' => 'Default value field is required',
            'categoryId.required' => 'Category field is required',
        ];
    }
}
