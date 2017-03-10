<?php

namespace App\Helpers\Logs;
use App\Helpers\Logs\UtilHelper;
use Illuminate\Http\Request, Authorizer;
use App\Models\LogRedis;

class NiuLogHelper
{
    public function getNiuLog($clientid){
        $key ="";
        return UtilHelper::getredis($clientid, 'log');
    }
    public function setNiuLog($arr){
        $key = "LOG_NIU_";
        $prefix = "Application_";
        $time = date('y_m_d');
        $cpid = $arr['cpid'];
        
        $total_niu = UtilHelper::getredis($prefix.$key.$cpid.'_'.$time, 'log');
        $total_niu+=1;
        UtilHelper::setredis($prefix.$key.$cpid.'_'.$time, $total_niu,'log', 0 );
        LogRedis::setDbLogRedis($prefix.$key.$cpid.'_'.$time, $total_niu, 0, $cpid );
        return $total_niu;
    }
    
}

