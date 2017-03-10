<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Cache,Response,Redis, DB;
class MerchantAppConfig extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app_config';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    public static function setCacheServer($app_id, $partner_id = '')
    {
        if ($partner_id === '') {
                $partner_id = 0;
            }
        $servers = self::where('appid', $app_id)->where('partner_id', '=', $partner_id)->get();
        if ($partner_id == 0) {
                $partner_id = '';
            }
        Cache::forever($app_id . $partner_id . '-servers', $servers->toArray());
       
    }
    
    
    public static function getServerList($app_id, $status = 1, $partner_id = 0)
    {
        return self::where('appid', $app_id)
            ->where('status_server', '=', $status)
            ->where('partner_id', '=', $partner_id)
            ->orderBy('serverid')
            ->get();
    }

    public static function getNewServerList($app_id, $status = 1, $partner_id = 0)
    {
        return self::where('appid', $app_id)
            ->where('is_new', '=', 1)
            ->where('partner_id', '=', $partner_id)
            ->where('status_server', '=', $status)
            ->orderBy('serverid', 'desc')
            ->get();
    }

    public static function getNewServer($app_id, $status = 1, $partner_id = 0)
    {
        $newestserver = self::where('appid', $app_id)
            ->where('is_new', '=', 1)
            ->where('status_server', '=', $status)
            ->where('partner_id', '=', $partner_id)
            ->orderBy('serverid','desc')
            ->first();

        if($newestserver){            
            return $newestserver->serverid;
        }else{            
            return 1;
        }
    }
    
    
    
}
