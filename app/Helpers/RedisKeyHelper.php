<?php

namespace App\Helpers;

use Mail;
use Illuminate\Support\Facades\Redis;
use App\Models\MerchantApp;
use App\Models\Merchant_app_cp;

class RedisKeyHelper {

    // Payment
    const PREFIX_TOPUP = 'Application_TOPUP_';
    const PREFIX_PAYMENT = 'Application_PAYMENT_';
    const PREFIX_REVENUE = 'Application_REVENUE_';
    // AU
    const PREFIX_DAU = 'Application_LOG_DAU_USER_';

    public static function getRevenueKey($cpid, $date) {
        if (empty($cpid)) {
            return;
        }
        $date = date('y_m_d', strtotime($date));
        switch ($cpid) {
            case 300000129:
                $cpid = 'vqc';
                $key = 'Application_REVENUE_' . $cpid . '_' . $date;
                break;
            case 300000184:
                $cpid = '17054245';
                $key = 'Application_PAYMENT_ZING_' . $cpid . '_' . $date;
                break;
            case 300000185:
                $cpid = '18903324';
                $key = 'Application_PAYMENT_ZING_' . $cpid . '_' . $date;
                break;
            case 300000186:
                $cpid = '17054245';
                $key = 'Application_PAYMENT_SH_' . $cpid . '_' . $date;
                break;
            case 300000187:
                $cpid = '18903324';
                $key = 'Application_PAYMENT_SH_' . $cpid . '_' . $date;
                break;
            case 300000188:
                $cpid = '17054245';
                $key = 'Application_PAYMENT_FACEBOOK_' . $cpid . '_' . $date;
                break;
            default:
                $cpid = $cpid;
                $key = 'Application_REVENUE_' . $cpid . '_' . $date;
                break;
        }
        return $key;
    }

    public static function updateRevenueKey($key, $cpid) {
        $redis = Redis::connection('revenue');
        $cache = $redis->get($key);
        if ($redis->exists($key)) {
            $revenue = 0;
            if (!empty($cache)) {
                $transactions = json_decode($cache, true);
                if (is_array($transactions) && !empty($transactions)) {
                    foreach ($transactions as $transaction) {
                        if (isset($transaction['amount']) && isset($transaction['uid']) && !empty($transaction['uid'])) {
                            if ($cpid == 300000001) {
                                if ($transaction['response']['errorCode'] == '200') {
                                    $revenue += $transaction['amount'];
                                }
                            } else {
                                if ($cpid == 300000185) {
                                    $revenue += $transaction['amount'] * 100;
                                } else {
                                    $revenue += $transaction['amount'];
                                }
                            }
                        }
                    }
                }
            }
            return $revenue;
        }
    }

}
