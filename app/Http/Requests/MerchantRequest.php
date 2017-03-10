<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Merchant_app;
class MerchantRequest extends Request
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
        $id = isset($_GET['appid'])?$_GET['appid']:'';
        return [
            'name'=>'required|min:6',
            'logo'=>'required|url',
            'slider'=>'required|url',
            'midder'=>'required|url',
            'content'=>'required|url',
            'thumb'=>'required|url',
            'profile'=>'required|url',
            'logo_url'=>'required|url',
            'slider_url'=>'required|url',
            'midder_url'=>'required|url',
            'content_url'=>'required|url',
            'url_news'=>'url',
            'url_homepage'=>'required|url',
            'thumb_url'=>'required|url',
            'profile_url'=>'required|url',
            'acount_special'=>'numeric',
            'facebook_id'=>'numeric',
            'slug'=>'required|unique:merchant_app,slug,'.$id
        ];
    }
    private function getSegmentFromEnd($position_from_end = 1) {
		$segments = $this->segments();
		return $segments[sizeof($segments) - $position_from_end];
		
	}
}
