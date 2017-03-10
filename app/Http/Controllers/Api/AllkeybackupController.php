<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Dau_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\Merchant_app_cp;
use Illuminate\Support\Facades\Redis;
use DB;
use Carbon\Carbon;
use App\Helpers\Logs\UtilHelper;
use App\Models\LogRedis;
class AllkeybackupController extends Controller {

    public function index(Request $request) {
        $key_pre = array();
        $key_pre[] = "Application_REVENUE_";
        $key_pre[] = "Application_TOPUP_";
        $key_pre[] = "Application_LOG_DAU_USER_";
        $key_pre[] = "Application_LOG_NIU_";
        $cps = Merchant_app_cp::all();
        $day = 2;
        for ($i = 0; $i <= $day; $i++) {
            $date = date("y_m_d", strtotime("-" . $i . " day", time()));
            foreach ($cps as $cp) {
                foreach ($key_pre as $keypr) {
                    $type = 1;
                    switch ($cp->cpid) {
                        case '300000129':
                            $cpid = 'vqc';
                            break;
                        case '300000141':
                            $type = 2;
                            $cpid = $cp->cpid;
                            break;
                        case '300000143':
                            $type = 2;
                            $cpid = $cp->cpid;
                            break;
                        case '300000177':
                            $type = 2;
                            $cpid = $cp->cpid;
                            break;
                        case '300000180':
                            $type = 2;
                            $cpid = $cp->cpid;
                            break;
                        default:
                            $cpid = $cp->cpid;
                            break;
                    }
                    if(strpos($keypr,'REVENUE')>0){
                        $type = 1;
                    }
                    if(strpos($keypr, 'TOPUP')>0){
                        $type = 1;
                    }
                    $key_redis = $keypr . $cpid . "_" . $date;
                   
                    if ($type == 2) {
                        $key_get = UtilHelper::getredis($key_redis, 'log');
                    } else {
                        $key_get = UtilHelper::getredis($key_redis);
                    }
                    echo $key_get."<br/>";
                    if(isset($key_get)){
                        LogRedis::setDbLogRedis($key_redis, $key_get, 0, $cp->cpid );
                    }
                     
                     echo "Done".$key_redis."<br/>";
                }
            }
            $key_partner = array();
            $key_partner[] = "Application_LOG_DAU_USER_FPAY_";
            $key_partner[] = "Application_LOG_NIU_FPAY_";
            $key_partner[] = "Application_PAYMENT_FACEBOOK_17054245_";
            $key_partner[] = "Application_PAYMENT_GARENA_17054245_";
            $key_partner[] = "Application_PAYMENT_SH_17054245_";
            $key_partner[] = "Application_PAYMENT_SH_18903324_";
            $key_partner[] = "Application_PAYMENT_TELCO_1001_";
            $key_partner[] = "Application_PAYMENT_TELCO_17054245_";
            $key_partner[] = "Application_PAYMENT_TELCO_18903324_";
            $key_partner[] = "Application_PAYMENT_TKFPAY_1001_";
            $key_partner[] = "Application_PAYMENT_TKFPAY_17054245_";
            $key_partner[] = "Application_PAYMENT_ZING_17054245_";
            $key_partner[] = "Application_PAYMENT_ZING_18903324_";
//            $key_partner[] = "";
            foreach ($key_partner as $key => $keypn) {
                $key_pay_redis = $keypn.$date;
                $key_get = UtilHelper::getredis($key_pay_redis);
                if(isset($key_get)){
                    LogRedis::setDbLogRedis($key_pay_redis, $key_get, 0,0);
                }
                
                echo "Done".$key_pay_redis."<br/>";
                
            }
            
            
            
        }
         if ($request->has('prkey')) {
            $key_redis = $request->input("prkey");
            $key_get = UtilHelper::getredis($key_redis);
                if(isset($key_get)){
                    LogRedis::setDbLogRedis($key_redis, $key_get, 0,0);
                }
                
                echo "Success update key ".$key_redis."<br/>";
            
        }

    }

}
