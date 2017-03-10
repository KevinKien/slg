<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use DB;

class AppInfor extends Model {
    protected $table = 'merchant_app';
    
    public static function check_approval($client_id){            
        //@todo
        //get game info from cache
        $cache_key = 'check_approval' . $client_id;
        $time = 10 * 60;
        // if cache not found
        if (!Cache::has($cache_key)) {
            $list_appinfor_querry = DB::table('merchant_app')
                    ->where('clientid', $client_id)
                    ->get();     
            $value = json_encode($list_appinfor_querry);
            // then save to cache
           Cache::add($cache_key, $value, $time);
        }        
        // return gameinfo from cache
        $value = Cache::get($cache_key);
        $obj = json_decode($value);        
        return $obj;
    }
    
    public static function list_appinfor($client_id){            
        //@todo
        //get game info from cache        
        $cache_key = 'api_home_page' . $client_id;
        
        $time = 60 * 60;
        // if cache not found
        if (!Cache::has($cache_key)) {
            $list_appinfor_querry = DB::table('merchant_app')
                    ->where('clientid', $client_id)
                    ->get();     
            $value = json_encode($list_appinfor_querry);
            // then save to cache
            Cache::add($cache_key, $value, $time);
        }        
        // return gameinfo from cache
        $value = Cache::get($cache_key);
        $obj = json_decode($value);        
        return $obj;
    }
    
}
?>
