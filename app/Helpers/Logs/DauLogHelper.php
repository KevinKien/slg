<?php

namespace App\Helpers\Logs;
use App\Helpers\Logs\UtilHelper;
use Illuminate\Http\Request, Authorizer;
use App\Models\LogRedis;

class DauLogHelper
{
    public function getDauLog($clientid){
        $key ="";
        return UtilHelper::getredis($clientid, 'log');
    }
    public function setDauLog($arr){
        $key = "LOG_DAU_USER_";
        $prefix = "Application_";
        $time = date('y_m_d');
        $userid = $arr['userid'];
        $cpid = $arr['cpid'];
        
        $user_dau = UtilHelper::getredis($prefix.$key.$time.'_'.$userid,'log');
        $total_dau = UtilHelper::getredis($prefix.$key.$cpid.'_'.$time, 'log');
        if(empty($user_dau)){
            $total_dau = $total_dau + 1;
            UtilHelper::setredis($prefix.$key.$cpid.'_'.$time, $total_dau,'log', 0 );
            UtilHelper::setredis($prefix.$key.$time.'_'.$userid,1 ,'log');
            LogRedis::setDbLogRedis($prefix.$key.$time.'_'.$userid, 1, 3, $cpid, $userid);
            LogRedis::setDbLogRedis($prefix.$key.$cpid.'_'.$time, $total_dau, 0, $cpid );
        }
        
        return $total_dau;
        
    }
    
}