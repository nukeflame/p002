<?php

namespace App\Http\Requests\Period;

use Illuminate\Foundation\Http\FormRequest;

class PeriodRequest extends FormRequest
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
            'begining_date' => 'required',
            'ending_date' => 'required',
            'is_current' => 'required',
            'pay_month' => 'required',
            'pay_year' =>'required',
        ];
    }
}
