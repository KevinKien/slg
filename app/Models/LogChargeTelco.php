<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class LogChargeTelco extends Model {

    protected $table = 'log_charge_coin_telco';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'trans_id', 'card_code', 'card_seri', 'card_type', 'order_mobile', 'response', 'coin', 'amount', 'ip', 'partner_trans_id', 'partner_type', 'payment_status', 'access_token'];

    public static function getLogChargeCardByTransid($trans_id) {
        $rs = static::where('trans_id', $trans_id)
                        ->where('payment_status', 'order')->first();
        if (is_object($rs)) {
            return $rs;
        }
        return FALSE;
    }

    public static function getLog($uid) {
        return self::where('uid', $uid)
                        ->whereBetween('created_at', [Carbon::now()->subMonth(3), Carbon::now()])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
    }

    public static function getLogCardPending() {
        return self::where('payment_status', 'Pending')
                        ->orderBy('created_at', 'desc');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'uid', 'id');
    }            
    
    public static function getLogGDTelco($arr){                   
        $data = DB::table('log_charge_coin_telco')
                        ->join('users', 'users.id', '=', 'log_charge_coin_telco.uid')
                        ->select('log_charge_coin_telco.*', 'users.name')
                        ->where('log_charge_coin_telco.created_at', '>=', date('Y-m-d:H:i:s', $arr['dateform']))
                        ->where('log_charge_coin_telco.created_at', '<=', date('Y-m-d:H:i:s', $arr['dateto']))
                        ->where('users.name', 'LIKE', '%'.$arr['username'].'%')
                        ->where(function ($query) use ($arr) {                            
                                if ($arr['serial'] != '') {                                        
                                    $query->where('log_charge_coin_telco.card_seri', 'LIKE', '%'.$arr['serial'].'%');
                                }
                            })                        
                        ->where('log_charge_coin_telco.trans_id', 'LIKE', '%'.$arr['transaction_id'].'%')
                        ->where('log_charge_coin_telco.card_type', 'LIKE', '%'.$arr['card_type'].'%')                      
                        ->where(function ($query) use ($arr) {
                                if ($arr['status'] == 'success') {                                    
                                    $query->where('log_charge_coin_telco.payment_status', 'LIKE', '%Giao dịch thành công%')
                                    ->orWhere('log_charge_coin_telco.payment_status', 'LIKE', '%success%')
                                    ->orWhere('log_charge_coin_telco.payment_status', 'LIKE', '%Thanh cong%');
                                } else if ($arr['status'] == 'fail') {                                    
                                    $query->where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%Giao dịch thành công%')
                                    ->Where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%success%')
                                    ->Where('log_charge_coin_telco.payment_status', 'NOT LIKE', '%Thanh cong%');
                                }
                            })
                        ->where(function ($query) use ($arr) {                            
                                if ($arr['amount'] != '') {                                        
                                    $query->where('log_charge_coin_telco.amount', '=', $arr['amount']);
                                }
                            })                        
                        ->get();
        
        return $data;
    }
    
    public static function getLogGDTelcobydate($dateform) {
//        if(!isset($_GET['bydate'])){
//            $dateform = strtotime(date('Y-m-d', time()).'%');
//        }else{
//            $dateform = strtotime(date('Y-m-d', $_GET['bydate']).'%');
//        }
//        print_r($dateform);die;
        $data = DB::table('log_charge_coin_telco')
                ->join('users', 'users.id', '=', 'log_charge_coin_telco.uid')
                ->join('cash_info', 'cash_info.uid', '=', 'log_charge_coin_telco.uid')
                ->select(DB::raw('count(amount) as Numbertag'), 'log_charge_coin_telco.uid', 'log_charge_coin_telco.amount', 'users.name', 'cash_info.coins', 'log_charge_coin_telco.created_at')
                ->where('log_charge_coin_telco.payment_status', 'like', '%thành công%')
                ->where('log_charge_coin_telco.created_at', 'like', $dateform)
                ->orwhere('log_charge_coin_telco.payment_status', '=', 'success')
                ->where('log_charge_coin_telco.created_at', 'like', $dateform)
                ->groupBy('log_charge_coin_telco.amount')
                ->groupBy('users.name')
                ->get();
//        print_r($data);

        return $data;
    }

    public static function getLogGDTelcobytype($dateform) {
//        if(!isset($_GET['bydate'])){
//            $dateform = strtotime(date('Y-m-d', time()).'%');
//        }else{
//            $dateform = strtotime(date('Y-m-d', $_GET['bydate']).'%');
//        }
//        print_r($dateform);die;
        $data = DB::table('log_charge_coin_telco')
                ->join('users', 'users.id', '=', 'log_charge_coin_telco.uid')
                ->join('cash_info', 'cash_info.uid', '=', 'log_charge_coin_telco.uid')
                ->select('log_charge_coin_telco.uid', 'log_charge_coin_telco.amount', 'log_charge_coin_telco.card_type', 'log_charge_coin_telco.card_code', 'log_charge_coin_telco.card_seri')
                ->where('log_charge_coin_telco.payment_status', 'like', '%thành công%')
                ->where('log_charge_coin_telco.created_at', 'like', $dateform)
                ->orwhere('log_charge_coin_telco.payment_status', '=', 'success')
                ->where('log_charge_coin_telco.created_at', 'like', $dateform)
                ->get();
//        print_r($data);die;

        return $data;
    }

    public static function getOrderAtmTransByDay() {


        //$logs = $logs->whereDate('request_time', '>=', $start)->whereDate('request_time', '<=', $end);
        // cache time run crontab
        $cache_key = 'atm_check_trans';

        if (!Cache::has($cache_key)) {
            $start = Carbon::yesterday();
        } else {
            $start = Cache::get($cache_key);
            $start = !empty($start) ? $start : Carbon::yesterday();
        }
        $rs = static::where('partner_type', 'Banknet')
                ->where('payment_status', 'order')
                ->where('created_at', '>=', $start->format('Y-m-d H:i:s'))
                 ->orderBy('id', 'desc')
                ->get();
        return $rs;
    }

}
