<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CheckProductRequest extends Request
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
            'product_name'=> 'required|min:4',
            'product_price'=>'required|numeric|min:1',
            'amount_fpay'=>'required|numeric|min:100',
            'product_description'=>'required',
        ];
    }
}
