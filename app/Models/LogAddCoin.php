<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class LogAddCoin extends Model {

    protected $table = 'log_coin_restore';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id', 'uid', 'trans_id', 'card_code', 'card_seri', 'card_type', 'order_mobile', 'response', 'coin', 'amount', 'ip', 'partner_trans_id', 'partner_type', 'payment_status', 'access_token'];




    public static function getLogAddCoins() {
        $result = [];
        //check lấy log giao dịch từ ngày n đến ngày n
        if (!isset($_GET['dateform'])) {
            $dateform = strtotime(date('Y-m-d', time()) . ' 00:00:00');
        } else {
            $dateform = strtotime(date('Y-m-d', $_GET['dateform']) . ' 00:00:00');
        }
        if (!isset($_GET['dateto'])) {
            $dateto = strtotime(date('Y-m-d', time()) . ' 23:59:59');
        } else {
            $dateto = strtotime(date('Y-m-d', $_GET['dateto']) . ' 23:59:59');
        }

        $data = DB::table('log_coin_restore')
                //->select('log_coin_restore.*')
                ->where('created_at', '>', date('Y-m-d:H:i:s', $dateform))
                ->where('created_at', '<', date('Y-m-d:H:i:s', $dateto))
                ->get();

        return $data;
    }

}
