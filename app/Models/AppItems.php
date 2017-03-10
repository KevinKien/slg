<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class AppItems extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'item_name', 'item_price', 'item_description'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public static function getItemsByClientId($client_id) {
        $cache_key = 'app_items_' . $client_id;
        $time = 60 * 60;
        if (!Cache::has($cache_key)) {
            $obj = static::where('client_id', $client_id)
                    ->orderBy('id', 'asc')
                    ->get();
            $value = json_encode($obj);
            Cache::add($cache_key, $value, $time);
        }
        
        $value = Cache::get($cache_key);
        $obj = json_decode($value);

        return $obj;
    }

}
