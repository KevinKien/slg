<?php

namespace App\Helpers\Logs;
use App\Helpers\Logs\UtilHelper;
use Illuminate\Http\Request, Authorizer;
use App\Models\LogRedis;
class RevenueLogHelper
{
    public function getRevenue($clientid){
        $key ="";
        return UtilHelper::getredis($clientid, 'revenue');
    }
    //    Dau vao
//        $arr = [
//            'cpid' ="",
//            'uid' ="",
//            'telco' ="",
//            'clientid' ="",
//            'serial' ="",
//            'amount' ="",
//            'code' ="",
//            ............
//        ];
    public function setRevenue($arr){
        
        $key = "REVENUE_";
        $key_total = "REVENUE_TOTAL_";
        $prefix = "Application_";
        $time = date('y_m_d');
        if(!isset($arr['cpid'])| !isset($arr['uid'])){
            return FALSE;
        }
        $cpid = $arr['cpid'];
        
        $app = app();
        $redis_logs = UtilHelper::getredis($prefix.$key.$cpid."_".$time, 'revenue');
        $data_log = array();
        if (!empty($redis_logs)) {
            $data_log = json_decode($redis_logs);
        }
        if (is_array($data_log)) {
            $app = app();
            $obj = $app->make('stdClass');
            $obj->cpid = $arr['cpid'];
            $obj->uid = $arr['uid'];
            $obj->telco = isset($arr['telco'])?$arr['telco']:'';
            $obj->clientid = isset($arr['clientid'])?$arr['clientid']:'';
            $obj->serial = isset($arr['serial'])?$arr['serial']:'';
            $obj->amount = isset($arr['amount'])?$arr['amount']:'';
            $obj->code = isset($arr['code'])?$arr['code']:'';
            $obj->time = date('H:i:s d/m/Y');
            $obj->data = json_encode($arr);
            $obj->response = isset($arr['status'])?$arr['status']:'';
            LogRedis::setDbLogRedis($prefix."amount_".$key.$cpid."_".$time,json_encode($obj), 32, $cpid, $arr['uid']);
            $data_log[] = $obj;
        }
        $log = json_encode($data_log);
        $time_log = 70 * 24 * 60 * 60;
        UtilHelper::setredis($prefix.$key.$cpid."_".$time, $log, 'revenue' , $time_log);
        LogRedis::setDbLogRedis($prefix.$key.$cpid."_".$time, $log, 0 , $cpid);
        $total_revenue = UtilHelper::getredis($prefix.$key_total.$cpid.'_'.$time);
        $total_revenue += isset($arr['amount'])?$arr['amount']:0;
        UtilHelper::setredis($prefix.$key_total.$cpid.'_'.$time, $total_revenue,'revenue' , 0 );
        
        LogRedis::setDbLogRedis($prefix.$key_total.$cpid.'_'.$time, $total_revenue, 0, $cpid );
        return $total_revenue;
    }
    
}