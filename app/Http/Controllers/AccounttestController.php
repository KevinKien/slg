<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\Accounttest,
    App\Models\MerchantApp;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AccounttestController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex() {
        //return view('compen.index');
        $account = Accounttest::getAccount();

        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $game1 = 1001;
        $listgame = DB::table('merchant_app')
            ->lists('merchant_app.name','merchant_app.id');
        return view('user.accounttest', compact('account','listgame','game1','page'));
    }

    public function getSearch(Request $request){
        $game = "";
        $game1 = "";
        if(empty($request)){
            $game1 = 1001;
        }else{
            $game = trim($request->get('game1'));
            $game1 = trim($request->get('game1'));

        }
        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $account = Accounttest::searchAccount($game);
        $listgame = DB::table('merchant_app')
            ->lists('merchant_app.name','merchant_app.id');
        return view('user.accounttest', compact('account','listgame','game1','page'));
    }
    public function getDelete($id) {
//        print_r($id);die;
        $game1 = 1001;
        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        Accounttest::deleteAccid($id);
        $account = Accounttest::getAccount();
        $listgame = DB::table('merchant_app')
            ->lists('merchant_app.name','merchant_app.id');

        return view('user.accounttest', compact('account','listgame','game1','page'));
    }

    public function postInsert(Request $request) {
        $this->validate($request, [
            'keyword' => 'required|min:1|max:64'
        ]);
        $keyword = trim($request->get('keyword'));
        $game = trim($request->get('game'));
//        $check = DB::table('users')
//            ->Where('id', $keyword)
//            ->first();
//        $check1 = DB::table('users')
//            ->where('fid',$keyword)
//            ->first();
//        tam thoi comment khi merge server fpay vao se khoi phuc
        $check =1;
        $check1 =1;
        if(!empty($check) || !empty($check1)){

            $check2 = DB::table('testid')
                ->where('test_id',$keyword)
                ->where('game',$game)
                ->first();
            if(!empty($check2)){
                Session::flash('flash_error', 'id of game exits');
            }else{
                if(!empty($check)){
                    Session::flash('flash_success', 'The id user add successfully.');
                    Accounttest::insertAccid($keyword,$game,'slg');}
                else{
                    Session::flash('flash_success', 'The id user add successfully.');
                    Accounttest::insertAccid($keyword,$game,'fpay');
                }

            }
        }else{
            Session::flash('flash_error', 'id not exits');
        }

        return redirect()->route('accounttest.index');
    }



}
