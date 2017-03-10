<?php

namespace App\Helpers\Logs;
use App\Helpers\Logs\UtilHelper;
use Illuminate\Http\Request, Authorizer;
use App\Models\LogRedis;
class TopupLogHelper
{
    public function getTopup($clientid){
        $key ="";
        return UtilHelper::getredis($clientid, 'topup');
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
    public function setTopup($arr){
        
        $key = "TOPUP_";
        $key_total = "TOPUP_TOTAL_";
        $prefix = "Application_";
        $time = date('y_m_d');
        if(!isset($arr['cpid'])| !isset($arr['uid'])){
            return FALSE;
        }
        $cpid = $arr['cpid'];
        
        $app = app();
        $redis_logs = UtilHelper::getredis($prefix.$key.$cpid."_".$time, 'telco');
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
            $obj->serial = isset($arr['serial'])?$arr['serial']:'';
            $obj->clientid = isset($arr['clientid'])?$arr['clientid']:'';
            $obj->amount = isset($arr['amount'])?$arr['amount']:'';
            $obj->device_id = isset($arr['device_id'])?$arr['device_id']:'';
            $obj->os_id = isset($arr['os_id'])?$arr['os_id']:'';
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
//        UtilHelper::setredis($prefix."amount_".$key.$cpid."_".$time, $log, 'revenue' , $time_log);
        LogRedis::setDbLogRedis($prefix.$key.$cpid."_".$time, $log, 0 , $cpid);
        $total_telco = UtilHelper::getredis($prefix.$key_total.$cpid.'_'.$time);
        $total_telco += isset($arr['amount'])?$arr['amount']:0;
        UtilHelper::setredis($prefix.$key_total.$cpid.'_'.$time, $total_telco,'revenue' , 0 );
        
        LogRedis::setDbLogRedis($prefix.$key_total.$cpid.'_'.$time, $total_telco, 0, $cpid );
        return $total_telco;
    }
    
}