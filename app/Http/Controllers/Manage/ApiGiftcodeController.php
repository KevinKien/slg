<?php

namespace App\Http\Controllers\Manage;

use App\Models\UserGiftcode;
use App\Http\Controllers\Controller;
use GameHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB, Session, App\User, Kodeine\Acl\Models\Eloquent\Role;
use App\Helpers\Logs\UtilHelper;

class ApiGiftcodeController extends Controller {

    public function __construct(Request $request) {
        
    }
    public function giftcodes(Request $request){ 
        
        $uid = $request->get('uid');
        $appid = $request->get('appid');
        $type = $request->get('type');
        
        if($uid == null){
            return 1;
        }
        $giftcode = UserGiftcode::getgitfcode($uid,$appid,$type);        
        return $giftcode;
    }
    

}
