<?php
namespace App\Http\Controllers\Manage;
use Auth;
use App\Models\Partner_info;
use App\Models\MerchantApp;
use App\Models\Merchant_app_cp;
use App\Models\MerchantAppConfig;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use App\Http\Requests\CheckServerRequest;
use App\Helpers\Games\GameServices;
use DB, Session,Response,Cache, App\User, Kodeine\Acl\Models\Eloquent\Role;
use App\Helpers\Logs\UtilHelper;

//use App\User;
class ServerController extends Controller
{
//     public function __construct(Request $request)
//    {
//        $this->beforeFilter(function () use ($request) {
//            $callback = $request->url();
//            if (!Auth::check()) {
//                return redirect('http://id.slg.vn/auth/login?callback=' . $callback);
//            }
//        });
//    }

    public function missingMethod($parameters = array())
    {

//        return view('errors.404');
        return redirect('server/index');
    }
    public function getIndex()
    {

        $results_total=DB::table('merchant_app_config')
            ->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
            ->get();
        $results = DB::table('merchant_app_config')
            ->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
            ->paginate(10);
        $listgame =DB::table('merchant_app')
            ->get();
        $game1 = 1;
            //->get();
            //print_r($results);die;
        return view('/server/list_sever', compact('results','results_total','listgame','game1'));
    }

    public function getSearch(Request $request)
    {
        $game = "";
        $game1 = "";
        if(empty($request)){
            $game1 = 1;
        }else{
            $game = trim($request->get('game1'));
//            print_r($game);die;
            $game1 = trim($request->get('game1'));

        }
        $results_total=DB::table('merchant_app_config')
            ->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
            ->get();

        $listgame =DB::table('merchant_app')
            ->get();

        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $results = '';
        if($game == 1){
            $results = DB::table('merchant_app_config')
                ->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
                ->paginate(10);
        }else{
            $results = DB::table('merchant_app_config')
                ->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
                ->where('appid',$game)
                ->paginate(10);}
//        print_r($game);die;

        //->get();
        //print_r($results);die;
        return view('/server/list_sever', compact('results','results_total','listgame','game1','page'));
    }
    public function getAdd()
    {

        $marchent_app = MerchantApp::All();
        $parner = Partner_info::all();
        return view('/server/add_sever', compact('parner', 'marchent_app'));
    }

    public function postIndex(CheckServerRequest $request)
    {
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('serverid', 'servername');
        $server = DB::table('merchant_app_config')
            ->where('appid',$dulieu_tu_input["appid"])
            ->where('partner_id',$dulieu_tu_input["partner_id"])
            ->where('serverid',$dulieu_tu_input["serverid"])
            ->get();
        if(empty($server)){
            $merchant = new MerchantAppConfig;
            //Lấy thông tin từ các input đưa vào
            //trong model Merchant_app_cp
            $merchant->appid = $dulieu_tu_input["appid"];
            $merchant->partner_id = $dulieu_tu_input["partner_id"];
            // $merchant->app_id = $dulieu_tu_input['game'];
            $merchant->serverid = $dulieu_tu_input["serverid"];
            $merchant->servername = $dulieu_tu_input['servername'];
            $merchant->status_server =$dulieu_tu_input['status'];
            $merchant->domain_server = $dulieu_tu_input["serverdomain"];
            $merchant->is_new = $dulieu_tu_input["optionsRadios"];
            $merchant->save();            
            Session::flash('flash_success', 'The sever added successfully.');
            MerchantAppConfig::setCacheServer($dulieu_tu_input["appid"],$dulieu_tu_input["partner_id"]);
            return redirect('/server');
        }else{
            Session::flash('flash_error', 'id server of game exits');
            $marchent_app = MerchantApp::All();
            return view('/server/add_sever', compact('parner', 'marchent_app'));
        }
    }

    public function getEdit()
    {
        $id = $_GET['id'];
        $results = DB::table('merchant_app_config')
            //->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
            ->where('merchant_app_config.configid', '=', $id)->
            get();
        foreach ($results as $row) {
        }
        $marchent_app1 = DB::table('merchant_app')->where('id', '=', $row->appid)->
        get();
        $marchent_app2 = DB::table('merchant_app')->where('id', '!=', $row->appid)->
        get();
        $parner1 = "";
        if($row->partner_id != 0){
            $parner1 = DB::table('partner_info')->where('partner_id', '=', $row->partner_id)->
            get();
        }        
        $parner2 = DB::table('partner_info')->where('partner_id', '!=', $row->partner_id)->
        get();
        return view('/server/edit_sever', compact('marchent_app1','marchent_app2','parner1','parner2', 'results'));
    }

    public function postEdit(CheckServerRequest $request)
    {
        $id = $_GET['id'];
        $idserverold =  DB::table('merchant_app_config')
            ->select('serverid','appid')
            ->where('configid', $id)->first();
        $dulieu_tu_input = $request->all();
        //print_r($dulieu_tu_input["optionsRadios"]);die;
        $dulieu_tu_input1 = $request->old();
        $request->flashOnly('serverid', 'servername');
        $server = DB::table('merchant_app_config')
            ->where('appid',$dulieu_tu_input["appid"])
            ->where('partner_id',$dulieu_tu_input["partner_id"])
            ->where('serverid',$dulieu_tu_input["serverid"])
            ->get();
        if($idserverold->serverid == $dulieu_tu_input["serverid"] && $idserverold->appid == $dulieu_tu_input["appid"]){

            $merchant = new MerchantAppConfig;
            DB::table('merchant_app_config')->where('configid', $id)->update(
                ['appid' => $dulieu_tu_input["appid"],
                    'partner_id' => $dulieu_tu_input["partner_id"],
                    'serverid' => $dulieu_tu_input["serverid"],
                    'servername' => $dulieu_tu_input["servername"],
                    'status_server' => $dulieu_tu_input["status"],
                    'domain_server' => $dulieu_tu_input["serverdomain"],
                    'is_new' => $dulieu_tu_input["optionsRadios"]]);
            Session::flash('flash_success', 'The sever updated successfully.');
            MerchantAppConfig::setCacheServer($dulieu_tu_input["appid"],$dulieu_tu_input["partner_id"]);
            return redirect('/server');
        }else{
            if(empty($server)){
                $merchant = new MerchantAppConfig;
                DB::table('merchant_app_config')->where('configid', $id)->update(
                    ['appid' => $dulieu_tu_input["appid"],
                        'partner_id' => $dulieu_tu_input["partner_id"],
                        'serverid' => $dulieu_tu_input["serverid"],
                        'servername' => $dulieu_tu_input["servername"],
                        'status_server' => $dulieu_tu_input["status"],
                        'domain_server' => $dulieu_tu_input["serverdomain"],
                        'is_new' => $dulieu_tu_input["optionsRadios"]]);
                Session::flash('flash_success', 'The sever updated successfully.');
                MerchantAppConfig::setCacheServer($dulieu_tu_input["appid"],$dulieu_tu_input["partner_id"]);
                return redirect('/server');
            }else{
                Session::flash('flash_error', 'id server of game exits');
                $id = $_GET['id'];
                $results = DB::table('merchant_app_config')
                    //->join('merchant_app','merchant_app.id', '=', 'merchant_app_config.appid')
                    ->where('merchant_app_config.configid', '=', $id)->
                    get();
                foreach ($results as $row) {
                }
                $marchent_app1 = DB::table('merchant_app')->where('id', '=', $row->appid)->
                get();
                $marchent_app2 = DB::table('merchant_app')->where('id', '!=', $row->appid)->
                get();
                return view('/server/edit_sever', compact('marchent_app1','marchent_app2', 'results'));
            }}
    }

    public function getDelete()
    {
        $id = $_GET['id'];
        DB::table('merchant_app_config')->where('configid', '=', $id)->delete();
        Session::flash('flash_success', 'You successfully deleted!');
        return redirect('/server');
    }

    public function store(){
        $data_ids = $_REQUEST['data_ids'];
        $data_id_array = explode(",", $data_ids);
        if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                DB::table('merchant_app_config')->where('configid', '=', $id)->delete();
            }
        }
    }

    public function store1()
    {

        $app_id =  $_GET['clientid'];
        $cache_key = 'appid_Infor_'.$app_id;
        $time = 10;
        if (!Cache::has($cache_key)) {
            $cpid_infor = DB::table('merchant_app')
                ->join('merchant_app_config','merchant_app_config.appid', '=', 'merchant_app.id')
                ->select('serverid','servername')
                ->where('clientid', $app_id)
                ->get();
            $value = json_encode($cpid_infor);
            Cache::add($cache_key, $value, $time);
        }

        $value = Cache::get($cache_key);
        return $value;

    }
    
    public function serverlist(){    
        
              $app_id = $_GET['app_id']; 
        $servers = GameServices::getServerList($app_id);
        
        $value = json_encode($servers);
        return $value;
    }
    
    public function serveruser(Request $request){          
        $app_id = $request->get('appid');        
        $uid = $request->get('uid');        
        
        $server_user = UtilHelper::gamePlaynow($app_id, $uid);
        //print_r($server_user);die;
        //$value = json_encode($server_user);
        return $server_user;
    }
    
    public function storegame1(){
    
        $game = Cache::get('active_game_list');
//        $data_id_array = json_decode($game[0]->images);
//        print_r($game);die;
        header('Content-Type: application/json');
        $value = json_encode($game,JSON_PRETTY_PRINT);
        return $value;
    }
}
?>