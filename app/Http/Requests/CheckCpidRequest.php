<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Merchant_app_cp;
class CheckCpidRequest extends Request
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
        $id = isset($_GET['cpi_name'])?$_GET['cpi_name']:'';
        return [
            'cpi_name' => 'required'
        ];
    }
}
