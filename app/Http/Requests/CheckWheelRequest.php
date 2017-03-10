<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CheckWheelRequest extends Request
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
            'eventname'=>'required',
            'imageitem'=>'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'turndial' =>'required|numeric|min:0',
            'rate1' => 'required|numeric|min:0',
            'rate2' => 'required|numeric|min:0',
            'rate3' => 'required|numeric|min:0',
            'rate4' => 'required|numeric|min:0',
            'rate5' => 'required|numeric|min:0',
            'rate6' => 'required|numeric|min:0',
            'rate7' => 'required|numeric|min:0',
            'rate8' => 'required|numeric|min:0',
            'item1' => 'required',
            'item2' => 'required',
            'item3' => 'required',
            'item4' => 'required',
            'item5' => 'required',
            'item6' => 'required',
            'item7' => 'required',
            'item8' => 'required',
            'quantity1' => 'required|numeric|min:1',
            'quantity2' => 'required|numeric|min:1',
            'quantity3' => 'required|numeric|min:1',
            'quantity4' => 'required|numeric|min:1',
            'quantity5' => 'required|numeric|min:1',
            'quantity6' => 'required|numeric|min:1',
            'quantity7' => 'required|numeric|min:1',
            'quantity8' => 'required|numeric|min:1',

        ];
    }

    public function messages()
    {
        return [
            'eventname.required' => 'Bạn cần nhập tên sự kiện',
            'imageitem.required' => 'Bạn cần nhập link ảnh',
            'imageitem.regex' => 'Không đúng định dạng của link',
            'phone.digits_between' => 'Số điện thoại là các chữ số dài từ 10 đến 20 ký tự',
            'turndial.required' => 'Bạn cần nhập số lượt quay',
            'turndial.numeric' => 'Số lượt quay phải là số',
            'turndial.min' => 'Số lượt quay không được nhỏ hơn 0',
            'rate1.required' => 'Bạn cần nhập tỷ lệ quay của item 1',
            'rate1.numeric' => 'Tỷ lệ quay phải là số',
            'rate1.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate2.required' => 'Bạn cần nhập tỷ lệ quay của item 2',
            'rate2.numeric' => 'Tỷ lệ quay phải là số',
            'rate2.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate3.required' => 'Bạn cần nhập tỷ lệ quay của item 3',
            'rate3.numeric' => 'Tỷ lệ quay phải là số',
            'rate3.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate4.required' => 'Bạn cần nhập tỷ lệ quay của item 4',
            'rate4.numeric' => 'Tỷ lệ quay phải là số',
            'rate4.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate5.required' => 'Bạn cần nhập tỷ lệ quay của item 5',
            'rate5.numeric' => 'Tỷ lệ quay phải là số',
            'rate5.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate6.required' => 'Bạn cần nhập tỷ lệ quay của item 6',
            'rate6.numeric' => 'Tỷ lệ quay phải là số',
            'rate6.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate7.required' => 'Bạn cần nhập tỷ lệ quay của item 7',
            'rate7.numeric' => 'Tỷ lệ quay phải là số',
            'rate7.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'rate8.required' => 'Bạn cần nhập tỷ lệ quay của item 8',
            'rate8.numeric' => 'Tỷ lệ quay phải là số',
            'rate8.min' => 'Tỷ lệ quay không được nhỏ hơn 0',
            'quantity1.required' => 'Bạn cần nhập số lượng của item 1',
            'quantity1.numeric' => 'Số lượng phải là số',
            'quantity1.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity2.required' => 'Bạn cần nhập số lượng của item 2',
            'quantity2.numeric' => 'Số lượng phải là số',
            'quantity2.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity3.required' => 'Bạn cần nhập số lượng của item 3',
            'quantity3.numeric' => 'Số lượng phải là số',
            'quantity3.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity4.required' => 'Bạn cần nhập số lượng của item 4',
            'quantity4.numeric' => 'Số lượng phải là số',
            'quantity4.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity5.required' => 'Bạn cần nhập số lượng của item 5',
            'quantity5.numeric' => 'Số lượng phải là số',
            'quantity5.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity6.required' => 'Bạn cần nhập số lượng của item 6',
            'quantity6.numeric' => 'Số lượng phải là số',
            'quantity6.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity7.required' => 'Bạn cần nhập số lượng của item 7',
            'quantity7.numeric' => 'Số lượng phải là số',
            'quantity7.min' => 'Số lượng không được nhỏ hơn 1',
            'quantity8.required' => 'Bạn cần nhập số lượng của item 8',
            'quantity8.numeric' => 'Số lượng phải là số',
            'quantity8.min' => 'Số lượng không được nhỏ hơn 1',
            'item1.required' => 'Bạn cần nhập item 1',
            'item2.required' => 'Bạn cần nhập item 2',
            'item3.required' => 'Bạn cần nhập item 3',
            'item4.required' => 'Bạn cần nhập item 4',
            'item5.required' => 'Bạn cần nhập item 5',
            'item6.required' => 'Bạn cần nhập item 6',
            'item7.required' => 'Bạn cần nhập item 7',
            'item8.required' => 'Bạn cần nhập item 8'

        ];
    }
}
