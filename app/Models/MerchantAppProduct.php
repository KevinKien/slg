<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\MerchantApp;
use DB;
class MerchantAppProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app_product';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    public static function list_product() {
         $log = DB::table('merchant_app_product')
                ->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_product.merchant_app_id')
                ->paginate(10);
        return $log;
    }
     public static function list_product_id($id) {
         $log = DB::table('merchant_app_product')
                ->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_product.merchant_app_id')
                ->where('merchant_app_product.product_id',$id) 
                ->get();
        return $log;
    }
    public static function get_products_by_appid($appid){
         $log=DB::table('merchant_app_product')
                 ->where('merchant_app_id',$appid)
                 ->get();                   
            if(!$log){
            return 31; 
            }
	return $log;           
		} 

    public static function getProduct($merchant_app_id, $status = 1)
    {
        return self::where('merchant_app_id', $merchant_app_id)
            ->orderBy('product_price')
            ->get();
    }
}
