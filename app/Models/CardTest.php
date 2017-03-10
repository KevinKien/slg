<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Util\LogController;

class CardTest extends Model
{
    protected $table = 'card_test';
    public $timestamps = false;
    protected $fillable = ['card_code', 'card_seri', 'card_type', 'created_by', 'issued_by', 'created_at', 'issued_at'];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function issuer()
    {
        return $this->belongsTo('App\Models\User', 'issued_by', 'id');
    }

    public static function charge($uid, $card_code, $card_seri, $card_type, $transid, $order_mobile, $ip, $request, $validator)
    {
        $card_test_types = [
            'MOBI' => 'Mobifone',
            'VINA' => 'Vinaphone',
            'VT' => 'Viettel',
        ];

        $card_test = self::where('card_code', $card_code)
            ->where('card_seri', $card_seri)
            ->where('card_type', $card_test_types[$card_type])
            ->whereNull('issued_by')
            ->whereNull('issued_at')
            ->first();

        if ($card_test) {
            $coin = $card_test->amount / 100;

            $added = CashInfo::incrementCoin($uid, $coin);

            if ($added) {
                $card_test->issued_by = $uid;
                $card_test->issued_at = time();
                $card_test->save();

//                LogController::logChargeCoin(
//                    $uid, $transid, $card_code, $card_seri, $card_type, $order_mobile, null, $coin, $card_test->amount, $ip, $transid, 'TEST', 'Nạp thẻ thành công.'
//                );

                $request->session()->flash('flash_success', 'Bạn vừa nạp thẻ Test thành công ' . $coin . ' Coin.');
                return redirect()->back()->withInput();
            } else {
                $request->session()->flash('flash_error', 'Nạp thẻ Test không thành công.');
                return redirect()->back()->withInput();
            }
        } else {
            $validator->errors()->add('card_code', 'Thẻ Test không hợp lệ.');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
