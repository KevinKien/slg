<?php

namespace App\Helpers\Logs;

use Illuminate\Http\Request,
    Authorizer;
use App\Models\MerchantApp;
use App\Models\MerchantAppConfig;
use App\Models\Merchant_app_cp;
use DB;
use Cache;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Redis;

class UtilHelper
{

    public static function getredis($key, $type = 'default')
    {
        switch ($type) {
            case 'log':
                $redis = Redis::connection('dblog');
                break;
            case 'revenue':
                $redis = Redis::connection('revenue');
                break;
            default:
                $redis = Redis::connection();
        }
        if (!empty($key)) {
            $redis_val = $redis->get($key);
            return $redis_val;
        }
        return FALSE;
    }

    public static function setredis($key, $val, $type, $timeexp = 172800)
    {
        switch ($type) {
            case 'log':
                $redis = Redis::connection('dblog');
                break;
            case 'revenue':
                $redis = Redis::connection('revenue');
                break;
            default:
                $redis = Redis::connection();
        }
        if (isset($val)) {
            if (is_object($val) || is_array($val)) {
                $val = json_encode($val);

            }
            $redis->set($key, $val);
            if ($timeexp > 0) {
                $redis->expire($key, $timeexp);
            }
            return TRUE;
        }
        return FALSE;
    }

    public static function timestart()
    {
        return microtime(true);
    }

    public static function timerun($start, $functionname)
    {
        $key = 'TIME_RUN_';
        $end = microtime(true);
        $time_run = $end - $start;
        Redis::set($key . $functionname, $time_run);
    }

    public static function getDefaultCpid($os_id, $clientid)
    {
        if (!$os_id) {
            $os_id = 3;
        }
        $app_arr = MerchantApp::where('clientid', $clientid)->first();
        if ($app_arr) {
            $app_id = $app_arr->id;
        } else {
            $app_id = '1008';
        }
        $cp_arr = Merchant_app_cp::where('app_id', $app_id)->where('os_id', $os_id)->first();
        if ($cp_arr) {
            $cpid = $cp_arr->cpid;
        } else {
            $cpid = '300000143';
        }

        return $cpid;
    }

    public static function gamePlaynow($appid, $userid, $serverid = 0, $partner_id = '')
    {
        $redis = Redis::connection('gameinfo');

        if (!isset($appid) || !isset($userid)) {
            return FALSE;
        } elseif (!empty($serverid)) {
            $redis->hSet('gameplaynow' . $appid . $partner_id, $userid, $serverid);
            return $serverid;
        } else {
            $serverid = $redis->hGet('gameplaynow' . $appid . $partner_id, $userid);
            if (empty($serverid)) {
                if ($partner_id === '') {
                    $partner_id = 0;
                }

                $serverid = MerchantAppConfig::getNewServer($appid, 1, $partner_id);
            }
            return $serverid;
        }
    }

}
