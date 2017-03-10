<?php

namespace App\Helpers\Games;

use Cache,
    App\Models\MerchantAppConfig;

class GameServices {

    /**
     * @param $app_id
     * @return MangaComicServices|OnePieceServices|null
     */
    public static function createService($app_id) {
        $service = null;

        //nhận cả id và clientid
        if ($app_id == 1001 || $app_id == 5173042681)
        {
            $service = new LinhVuongServices;
        } elseif ($app_id == 1008 || $app_id == 2044387389)
        {
            $service = new ThienLongServices;
        } elseif ($app_id == 17054245 || $app_id == 1286862090)
        {
            $service = new OnePieceServices;
        } elseif ($app_id == 18903324 || $app_id == 8657473775)
        {
            $service = new MangaComicServices;
        } elseif ($app_id == 18903329 || $app_id == 9194439505 || $app_id == 123456789) // test api
        {
            $service = new DaiThanhG3Services;
        } elseif ($app_id == 18903334 || $app_id == 8633283045)
        {
            $service = new TieuLongServices;
        } elseif ($app_id == 18903335 || $app_id == 4210935674)
        {
            $service = new TamQuocTruyenKyServices;
        }

        return $service;
    }

    /**
     * @param $app_id
     * @param int $partner_id
     * @return mixed
     */
    public static function getServerList($app_id, $partner_id = '') {
        $result = Cache::remember($app_id . $partner_id . '-servers', 60, function() use ($app_id, $partner_id) {
            if ($partner_id === '') {
                $partner_id = 0;
            }

            $servers = MerchantAppConfig::getServerList($app_id, 1, $partner_id);
            return $servers->toArray();
        });

        return $result;
    }   
    
    public static function getServerList1($app_id, $partner_id = '') {
        if (Cache::store('redis')->has($app_id . $partner_id . '-servers')) {
            return Cache::store('redis')->get($app_id . $partner_id . '-servers');
        } else {
            MerchantAppConfig::setCacheServer($dulieu_tu_input["appid"],$dulieu_tu_input["partner_id"]);
            return Cache::store('redis')->get($app_id . $partner_id . '-servers');
        }        
    }  
    

}
