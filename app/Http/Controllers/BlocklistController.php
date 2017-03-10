<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\Blocklist;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BlocklistController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex() {        
        $listusers = DB::table('blocked_list')
            ->get();
       
        return view('/blocked_list/blocked_list_users',compact('listusers'));
    }
    public function getAdd()
    {        
        return view('/blocked_list/add_block_user');
    }
    public function postAdd(Request $request) {              
        $time = date( 'Y-m-d H:i:s', time());        
        $input = $request->all(); 
        if(!isset($input["block_telco"])){
            $input["block_telco"] = '0';
        }
        if(!isset($input["block_visa_nganluong"])){
            $input["block_visa_nganluong"] = '0';
        }
        if(!isset($input["block_visa_napas"])){
            $input["block_visa_napas"] = '0';
        }
        if(!isset($input["block_atm_napas"])){
            $input["block_atm_napas"] = '0';
        }
        $users = DB::table('users')->select('id', 'name','fullname')
        ->where('name',$input["username"])           
        ->get();       
        
        if(isset($users[0]->id)){
            $response = Blocklist::firstOrNew(
                ['uid' => $users[0]->id,
                'username' => $input["username"]]);            
                $response->card_telco = $input["block_telco"];         
                $response->visa_nganluong = $input["block_visa_nganluong"];
                $response->visa_napas = $input["block_visa_napas"];
                $response->atm_napas = $input["block_atm_napas"];
                $response->coin_transfer = $input["optionstransfer"];
                $response->created_at = $time;   
                
            if ($response->exists){
                    // user already exists
                    Session::flash('flash_error', 'user is exits');                      
                    return redirect('/blocked-payment/add');
                }else {
                    // user created from 'new'; does not exist in database.
                    $response->save();
                    Session::flash('flash_success', 'The user added successfully.');           
                    return redirect('/blocked-payment');
                }                                   
        }else{                
            Session::flash('flash_error', 'user is not exits');                
            return redirect('/blocked-payment/add');
        }                      
        
    }   

    public function getEdit($id) {                       
        $listusers = DB::table('blocked_list')
            ->where('uid',$id)
            ->get();
        return view('/blocked_list/edit_block_user',compact('listusers'));
    }

    public function getSearch(Request $request) {
        $input = $request->all();  
        if(!empty($input['keyword'])){
            $listusers = DB::table('blocked_list')
            ->where('username',$input['keyword'])
            ->get();
        }else{
            $listusers = DB::table('blocked_list')            
            ->get();
        }
        
        
        return view('/blocked_list/blocked_list_users',compact('listusers'));
        
    }

    public function postUpdate(Request $request) {
        $input = $request->all(); 
        if(!isset($input["block_telco"])){
            $input["block_telco"] = '0';
        }
        if(!isset($input["block_visa_nganluong"])){
            $input["block_visa_nganluong"] = '0';
        }
        if(!isset($input["block_visa_napas"])){
            $input["block_visa_napas"] = '0';
        }
        if(!isset($input["block_atm_napas"])){
            $input["block_atm_napas"] = '0';
        }          
        
        DB::table('blocked_list')->where('username', $input["username"])->update(
            ['card_telco' => $input["block_telco"],
            'visa_nganluong' => $input["block_visa_nganluong"],
            'visa_napas' => $input["block_visa_napas"],
            'atm_napas' => $input["block_atm_napas"],                
            'coin_transfer' => $input["optionstransfer"]]);
             Session::flash('flash_success', 'The user added successfully.');           
             return redirect('/blocked-payment');    
                                                                           
    }
    public function postDelete() {
        
        $data_ids = $_REQUEST['data_ids'];
        $data_id_array = explode(",", $data_ids);
        if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                DB::table('blocked_list')->where('uid', '=', $id)->delete();
            }
        }
    }

}
