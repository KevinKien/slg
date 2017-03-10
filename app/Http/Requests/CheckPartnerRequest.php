<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CheckPartnerRequest extends Request
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
            'partner-name'=> 'required|min:4|alpha',
            'payment-url-callback'=>'required|url',
        ];
    }
}
