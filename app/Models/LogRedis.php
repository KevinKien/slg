<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core_partner_info;
use App\Models\Merchant_app_cp;
use DB;
use Carbon\Carbon;

class LogRedis extends Model {

    protected $table = 'log_redis';
    public $timestamps = true;
    protected $fillable = ['key', 'cpid', 'userid', 'value', 'valueback', 'timeexp', 'sizeofz'];

    public static function setDbLogRedis($key, $value, $timeexp = 0, $cpid = 0, $userid = 0) {
        if ($timeexp == 0) {
            $timeset = Carbon::now()->addYear();
        } else {
            $timeset = Carbon::now()->addDay($timeexp);
        }

        $insert_arr = array();
        $insert_arr['key'] = $key;
        $insert_arr['value'] = $value;
        $insert_arr['timeexp'] = $timeset;
        $insert_arr['updated_at'] = Carbon::now();


        if (!empty($cpid)) {
            $insert_arr['cpid'] = $cpid;
        }
        if ($userid > 0 || strpos($key, 'amount') > 0) {

            $insert_arr['userid'] = $userid;
            $insert_arr['created_at'] = Carbon::now();
            LogRedis::insert($insert_arr);
        } elseif (is_numeric($value)) {
            $update_arr = array();
            $update_arr['key'] = $key;
            $total_log = LogRedis::firstOrNew($update_arr);
            if ($value >= $total_log->value) {
                $insert_arr['value'] = $value;
            } elseif ($value < $total_log->value) {
                $insert_arr['valueback'] = $total_log->value;
                $insert_arr['value'] = $value;
            }
            $total_log->fill($insert_arr)->save();
        } else {
            $update_arr = array();
            $update_arr['key'] = $key;
            $total_log = LogRedis::firstOrNew($update_arr);
            $vallen = strlen($value);
            if ($vallen >= $total_log->sizeofz) {
                $insert_arr['value'] = $value;
            } elseif ($vallen < $total_log->sizeofz) {
                $insert_arr['valueback'] = $total_log->value;
                $insert_arr['value'] = $value;
            }
            $insert_arr['sizeofz'] = $vallen;
            $total_log->fill($insert_arr)->save();
        }
    }

    public static function getKeyDbLogRedis($key) {
        if (isset($key)) {
            $result_arr = LogRedis::where('key', $key)->select('id', 'cpid', 'value', 'sizeofz')->first();
            if (isset($result_arr)) {
                return $result_arr->attributes;
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }

    public static function getLogByKey($key) {
        $rs = static::where('key', $key)->get();
        return $rs;
    }

}
