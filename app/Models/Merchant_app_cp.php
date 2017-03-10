<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use DB;

class Merchant_app_cp extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app_cp';
    protected $primaryKey = 'cpid';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//    protected $hidden = ['password', 'remember_token'];
    public $timestamps = false;

    public static function getCpidInfor() {
        //get from cache
//        $cache_key = 'cpid_Infor';
//        $time = 10 * 60;
        //check data from cache
//        if(!Cache::has($cache_key)){
        //get from db
        $cpid_infor = DB::table('merchant_app_cp')
                ->join('partner_info', 'merchant_app_cp.partner_id', '=', 'partner_info.partner_id')
                ->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_cp.app_id')
                //->select('users.id', 'contacts.phone', 'orders.price')
                ->paginate(10);

        //set to cache                   
//            Cache::add($cache_key,$cpid_infor,$time);
//        }
        //get from cache
//        $value = Cache::get($cache_key);

        return $cpid_infor;
    }

    public function getCpidInforByCpid($cpid) {
        //@todo
        //get from cache
        $cache_key = 'cpid_Infor_by_cpid' . $cpid;
        $time = 10 * 60;
        //get from db
        if (!Cache::has($cache_key)) {
            $data = DB::table('merchant_app_cp')->join('partner_info', 'merchant_app_cp.partner_id', '=', 'partner_info.partner_id')->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_cp.app_id')->where('cpid', '=', $cpid)->
                    get();

            //set to cạche
            Cache::add($cache_key, $data, $time);
        }
        //get from cache
        $value = Cache::get($cache_key);
        return $value;
    }

    public function getPartnerInforByPartnerid($cpid) {
        //@todo
        //get from cache
        $cache_key = 'partner_Infor_by_cpid' . $cpid;
        $time = 10 * 60;
        //get from db
        if (!Cache::has($cache_key)) {
            $data = DB::table('merchant_app_cp')->join('partner_info', 'merchant_app_cp.partner_id', '=', 'partner_info.partner_id')->join('merchant_app', 'merchant_app.id', '=', 'merchant_app_cp.app_id')->where('cpid', '=', $cpid)->
                    get();

            //set to cạche
            Cache::add($cache_key, $data, $time);
        }
        //get from cache
        $value = Cache::get($cache_key);
        return $value;
    }

    public static function update_cpidInfor($dulieu_tu_input, $dulieu_tu_input1, $cpid) {
        //update cpid        
        if (isset($dulieu_tu_input1["ga-id"])) {
            DB::table('merchant_app_cp')->where('cpid', $cpid)->update(['cp_name' => $dulieu_tu_input['cpi_name'], 'partner_id' => $dulieu_tu_input["add-partner"],
                'app_id' => $dulieu_tu_input["add-appid"], 'ga_id' => $dulieu_tu_input1["ga-id"],
                'time_update' => time(),'show' => $dulieu_tu_input["Show"],'check_revenue' => $dulieu_tu_input["CheckRevenue"]]);
        } else {
            DB::table('merchant_app_cp')->where('cpid', $cpid)->update(['cp_name' => $dulieu_tu_input['cpi_name'], 'partner_id' => $dulieu_tu_input["add-partner"],
                'app_id' => $dulieu_tu_input["add-appid"], 'ga_id' => $dulieu_tu_input["ga-id"],
                'time_update' => time(),'show' => $dulieu_tu_input["Show"],'check_revenue' => $dulieu_tu_input["CheckRevenue"]]);
        }

        //clear cache
        Cache::forget('cpid_Infor');
        Cache::forget('cpid_Infor_by_cpid' . $cpid);
        return 200;
    }

    public static function delele_cpid($cpid) {
        DB::table('merchant_app_cp')->where('cpid', '=', $cpid)->delete();
        Cache::forget('cpid_Infor');
        return 200;
    }

    public static function getMerchantbyparnerId() {
        $result = Merchant_app_cp::where('partner_id', '=', 100000010)
                ->lists('cp_name', 'cpid');
        return $result;
    }

    public static function getCpidUser($provider, $gameid) {
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

    public static function getCpidUser1($provider = null, $app_id) {
        $mechant_cp = "";
        $provider = strtolower($provider);

        switch ($provider) {
            case 'mwork':
                $provider = 'Mwork';
                break;
            case 'fpay':
                $provider = 'Fpay';
                break;
            case 'zing':
                $provider = 'Zing';
                break;
            case 'soha':
                $provider = 'Soha';
                break;
            case 'facebook':
                $provider = 'Facebook';
                break;
            default :
                $provider = null;
                break;
        }
        if (isset($provider)) {
            $mechant_cp = self::where('app_id', $app_id)
                    ->where('describe', 'like', $provider . '%')
                    ->first();
            if (!isset($mechant_cp->cpid)) {
                $mechant_cp = self::where('app_id', $app_id)
                        ->first();
            }
        } else {
            $mechant_cp = self::where('app_id', $app_id)
                    ->first();
        }
        return $mechant_cp->cpid;
    }

    public function Partner_info() {
        return $this->hasOne('App\Partner_info');
    }

    public function Merchant_app() {
        return $this->hasOne('App\MerchantApp');
    }

    public static function getLogpartner(){
        $cpid = $_GET['cpid'];
        $result = DB::table('merchant_app_cp')
            ->select('describe')
            ->where('app_id',$cpid)
            ->orwhere('cpid',$cpid)
            ->first();
        return $result->describe;
    }

    public static function findOrCreate($uid) {
        $obj = static::where('uid', $uid)->firstOrFail();
        return $obj ? : new static;
    }

    public static function AllCp() {

        $cache_key = 'merchant_app_cp_list1';
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

}
