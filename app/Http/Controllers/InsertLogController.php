<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\InsertLog;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InsertLogController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex() {     
        $transid = "SML_" . '119045' . '_' . time() . '_' . rand();
        dd($transid);        
        return view('/blocked_list/blocked_list_users',compact('listusers'));
    }
    public function getAdd()
    {        
        return view('/blocked_list/add_block_user');
    }
    public function postAdd(Request $request) {              
                              
    }   

    public function getEdit($id) {                       
        
    }

    public function getSearch(Request $request) {        
        
    }

    public function postUpdate(Request $request) {        
                                                                           
    }
    public function postDelete() {        
    }

}
