<?php

namespace App\Http\Controllers\Manage;

use App\Models\UserGiftcode;
use App\Http\Controllers\Controller;
use GameHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB, Session, App\User, Kodeine\Acl\Models\Eloquent\Role;
use App\Helpers\Logs\UtilHelper;
use Facebook\FacebookRequest;

class ApiUserController extends Controller {

    public function __construct(Request $request) {
     
    }
    public function likepage(){      
       
        $request = new FacebookRequest(
          $session,
          'GET',
          '/me/likes/'
        );
        
        $response = $request->execute();
        $graphObject = $response->getGraphObject();
        print_r(1);die;
        /* handle the result */                
    }
    

}
