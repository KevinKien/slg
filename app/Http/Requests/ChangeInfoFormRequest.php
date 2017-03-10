<?php
namespace App\Http\Requests;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Response;

class ChangeInfoFormRequest extends FormRequest
{
    public function rules()
    {
        return [
            '_token' => 'required',
            'fullname' => 'required',
            'email' => 'email',
            'phone' => 'digits_between:10,20',
            'identify' => 'digits_between:9,20',
        ];
    }
    public function messages()
    {
        return [
             '_token.required' => 'cần có',
            'fullname.required' => 'Bạn cần nhập họ tên',
            'email.email' => 'Bạn cần nhập đúng định dạng email',
            'phone.digits_between' => 'Số điện thoại là các chữ số dài từ 10 đến 20 ký tự',
            'identify.digits_between' => 'Chứng minh thư là số dài từ 9 đến 20 ký tự',
        ];
    }

    public function authorize()
    {
        // Only allow logged in users
        // return \Auth::check();
        // Allows all users in
        return true;
    }

    // OPTIONAL OVERRIDE
    public function forbiddenResponse()
    {
        // Optionally, send a custom response on authorize failure 
        // (default is to just redirect to initial page with errors)
        // 
        // Can return a response, a view, a redirect, or whatever else
        return Response::make('Permission denied foo!', 403);
    }

    // OPTIONAL OVERRIDE
//    public function response()
//    {
//        // If you want to customize what happens on a failed validation,
//        // override this method.
//        // See what it does natively here: 
//        // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
//    }
}