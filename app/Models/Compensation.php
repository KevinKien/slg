<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Compensation extends Model {

    protected $table = 'cash_info';

    //protected $table2 = 'log_charge_coin_telco';


    public static function coinUser($id) {

        $coinsUser = DB::table('cash_info')
                ->where('uid', $id)
                ->get();
        return $coinsUser;
    }

    public static function transactionUser($id) {
        //lấy thông tin giao dịch thất bại
        $trans = DB::table('log_charge_coin_telco')
                ->join('users', 'users.id', '=', 'log_charge_coin_telco.uid')
                ->select('log_charge_coin_telco.*', 'users.name')
                ->where('log_charge_coin_telco.uid', $id)
                ->Where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%Giao dịch thành công%')
                ->where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%success%')
                ->Where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%Thanh cong%')
                ->orderBy('log_charge_coin_telco.created_at', 'desc')
                ->offset(0)
                ->take(20)
                ->get();
        return $trans;
    }

    public static function transactionInfo($id) {
        //lấy thông tin giao dịch thất bại
        $trans = DB::table('log_charge_coin_telco')
                ->join('users', 'users.id', '=', 'log_charge_coin_telco.uid')
                ->join('cash_info', 'cash_info.uid', '=', 'log_charge_coin_telco.uid')
                ->select('log_charge_coin_telco.*', 'users.name', 'cash_info.coins')
                ->where('log_charge_coin_telco.id', $id)
                ->get();
        return $trans;
    }

    public static function updateTransaction($status, $amount, $trans_id, $coin) {
        //cập nhật thông tin giao dịch
        $query = DB::table('log_charge_coin_telco')
                ->where('trans_id', $trans_id)
                ->update(
                array(
                    'payment_status' => $status,
                    'amount' => $amount,
                    'coin' => $coin
        ));
        return 200;
    }

    public static function updateCashInfo($uid, $coins) {
        //check ví người dùng        
        $query = DB::table('cash_info')
                ->where('uid', $uid)
                ->get();

        //nếu chưa có thì tạo ví + coins
        if (!isset($query[0]->uid)) {
            $query1 = DB::table('cash_info')
                    ->insert(
                    array('uid' => $uid,
                        'coins' => $coins
            ));
        } else {
            //có ví rồi thì + coins
            $coin = $query[0]->coins + $coins;
            $query2 = DB::table('cash_info')
                    ->where('uid', $uid)
                    ->update(
                    array(
                        'coins' => $coin
            ));
        }

        return 200;
    }

    //ghi log bù coin
    public static function insertLog($admin, $user, $amount, $coins, $trans_id, $time) {
        //ghi log bù coin        
        $query = DB::table('log_coin_restore')
                ->insert(
                array('admin' => $admin,
                    'user' => $user,
                    'amount' => $amount,
                    'coins' => $coins,
                    'trans_id' => $trans_id,
                    'created_at' => $time
        ));
        return 200;
    }

}

?>
