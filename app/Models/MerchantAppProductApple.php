<?php

namespace App\Models;

use DB;
use App\Models\MerchantApp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class MerchantAppProductApple extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app_produc_apple';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function list_product_apple() {
        $log = DB::table('merchant_app_produc_apple')
                ->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_produc_apple.merchant_app_id')
                ->paginate(10);
        return $log;
    }

    public static function list_product_apple_id($id) {
        $log = DB::table('merchant_app_produc_apple')
                ->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_produc_apple.merchant_app_id')
                ->where('merchant_app_produc_apple.product_apple_id', $id)
                ->get();
        return $log;
    }

    public static function get_products_apple_by_appid($appid) {
        $log = DB::table('merchant_app_produc_apple')
                ->where('merchant_app_id', $appid)
                ->get();
        if (!$log) {
            return 31;
        }
        return $log;
    }

    /*
     *  Return list item in store apple
     * 
     * 
     *      */

    public static function get_products_apple_by_client_id($client_id) {
        //key 
        $cache_key = 'apple_item_' . $client_id;
        $time = 10 * 60;
        // if cache not found
        if (!Cache::has($cache_key)) {
            $rs = DB::table('merchant_app_produc_apple')
                    ->where('client_id', $client_id)
                    ->get();
            $value = json_encode($rs);
            // then save to cache
            Cache::add($cache_key, $value, $time);
        }
        // return gameinfo from cache
        $value = Cache::get($cache_key);
        $obj = json_decode($value);
        return $obj;
    }

}
