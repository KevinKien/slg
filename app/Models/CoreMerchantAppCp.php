<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Cache;

class CoreMerchantAppCp extends Model
{

    protected $table = 'merchant_app_cp';
    protected $primaryKey = 'cpid';
    public $timestamps = false;

    public static function findOrCreate($uid)
    {
        $obj = static::where('uid', $uid)->firstOrFail();
        return $obj ?: new static;
    }

    public static function AllCp()
    {

        $cache_key = 'merchant_app_cp_list';
        $time = 60 * 60;
        if (!Cache::has($cache_key)) {
            $obj = static::where('show', 1)->get();
            $value = json_encode($obj);
            Cache::add($cache_key, $value, $time);
        }

        $value = Cache::get($cache_key);
        $obj = json_decode($value);
        return $obj;
    }

    public static function getCpidUser($provider, $gameid)
    {
        $allcp = self::AllCp();
        if (empty($provider)) {
            $provider = 'Fpay';
        }
        $provider = ucfirst($provider);

        foreach ($allcp as $cp) {
            if ($provider == $cp->describe && $gameid == $cp->app_id) {
                return $cp->cpid;
            }
        }
        return $provider . '_' . $gameid;
    }

    public static function setCache()
    {
        $cps = self::select('merchant_app_cp.*', 'partner_info.partner_name')
            ->join('partner_info', function ($join) {
                $join->on('merchant_app_cp.partner_id', '=', 'partner_info.partner_id');
            })->get();

        $partner_cp = [];

        foreach ($cps as $cp)
        {
            $partner_cp[$cp->cpid] = $cp->partner_name;
        }

        Cache::forever('partner_cp', $partner_cp);
    }
}
