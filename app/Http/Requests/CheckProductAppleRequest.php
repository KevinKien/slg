<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CheckProductAppleRequest extends Request
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
            'product_id'=>'required',
            'title'=>'required|min:4',
            //'product_name'=> 'required|min:4',
            'amount'=>'required|numeric|min:0',
            'money_in_game'=>'required|numeric|min:100',
            'description'=>'required',
        ];
    }
}
