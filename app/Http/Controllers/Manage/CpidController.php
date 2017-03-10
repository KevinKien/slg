<?php
namespace App\Http\Controllers\Manage;
use Auth;
use App\Models\Partner_info;
use App\Models\MerchantApp;
use App\Models\Merchant_app_cp;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoreMerchantAppCp;
use App\Http\Requests\CheckCpidRequest;
use DB, Session;

class CpidController extends Controller
{
    public function __construct(Request $request)
    {
        $this->beforeFilter(function () use ($request) {
            $callback = $request->url();
            if (!Auth::check()) {
                return redirect('http://id.slg.vn/auth/login?callback=' . $callback);
            }
        });
    }

    public function missingMethod($parameters = array())
    {

//        return view('errors.404');
        return redirect('cpid/index');
    }
    public function getIndex()
    {
        $merchant_app_cp = new Merchant_app_cp();
        $results_total=Merchant_app_cp::All();
        $results = $merchant_app_cp->getCpidInfor();
        $partner=DB::table('partner_info')
            ->get();
        $game1 = 1;
        return view('/cpid/list', compact('results','results_total','partner','game1'));
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
        $results_total=Merchant_app_cp::All();

        $partner=DB::table('partner_info')->get();

        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $results = '';
        if($game == 1){
            $results = DB::table('merchant_app_cp')
                ->join('partner_info','merchant_app_cp.partner_id', '=', 'partner_info.partner_id')
                ->join('merchant_app','merchant_app.id', '=', 'merchant_app_cp.app_id')
                //->select('users.id', 'contacts.phone', 'orders.price')
                ->paginate(10);
        }else{

            $results = DB::table('merchant_app_cp')
                ->join('partner_info','merchant_app_cp.partner_id', '=', 'partner_info.partner_id')
                ->join('merchant_app','merchant_app.id', '=', 'merchant_app_cp.app_id')
                ->where('merchant_app_cp.partner_id',$game)
                ->paginate(10);
        }
//        print_r($game);die;

        //->get();
        //print_r($results);die;
        return view('/cpid/list', compact('results','results_total','partner','game1','page'));
    }

    public function getAdd()
    {
        $parner = Partner_info::All();

        $marchent_app = MerchantApp::All();

        return view('/cpid/add', compact('parner', 'marchent_app'));
    }

    public function postIndex(CheckCpidRequest $request)
    {

        $dulieu_tu_input = $request->all();
        //print_r($dulieu_tu_input);die;
        $request->flashOnly('cpi_name');
        //  print($dulieu_tu_input["game"]);die;

        $merchant = new Merchant_app_cp;
        //Lấy thông tin từ các input đưa vào
        //trong model Merchant_app_cp

        $merchant->cp_name=$dulieu_tu_input['cpi_name'];
        $merchant->partner_id = $dulieu_tu_input["add-partner"];
        $merchant->app_id = $dulieu_tu_input['add-appid'];
        $merchant->ga_id = $dulieu_tu_input["google_code"];
        $merchant->os_id = $dulieu_tu_input["add-osid"];
        $merchant->time_update = time();
        $merchant->show = $dulieu_tu_input["Show"];
        $merchant->check_revenue = $dulieu_tu_input["CheckRevenue"];
        $merchant->save();
        Session::flash('flash_success', 'The cpid added successfully.');

//        CoreMerchantAppCp::setCache();

        return redirect('cpid');

    }

    public function getEdit(Request $request)
    {
        $merchant = new Merchant_app_cp();
        $merchantApp = new MerchantApp();

        $cpid = $request->cpid;
        $results = $merchant->getCpidInforByCpid($cpid);
        //print_r($results[0]->check_revenue);die;
        foreach ($results as $row) {
        }
        $parner = $merchant->getPartnerInforByPartnerid($row->partner_id);

        $marchent_app = $merchantApp->getMerchantAppById($row->id);

        return view('/cpid/edit', compact('parner', 'marchent_app', 'results'));
    }

    public function postEdit(CheckCpidRequest $request)
    {
        $cpid = $request->cpid;
        $dulieu_tu_input = $request->all();
        $dulieu_tu_input1 = $request->old();
        
        $request->flashOnly('cpi_name');

        $merchant = new Merchant_app_cp;

        $result = $merchant->update_cpidInfor($dulieu_tu_input,$dulieu_tu_input1,$cpid);

        if($result == 200){
            Session::flash('flash_success', 'The cpid updated successfully.');
        }else {
            Session::flash('flash_success', 'The cpid updated fail.');
        }

//        CoreMerchantAppCp::setCache();

        return redirect('cpid');
    }

    public function getDelete()
    {
        $merchant = new Merchant_app_cp();
        $cpid = $_GET['cpid'];
        $result = $merchant->delele_cpid($cpid);
        if($result == 200)
        {
            Session::flash('flash_success', 'The cpid deleted successfully.');
        }else{
            Session::flash('flash_success', 'The cpid deleted fail.');
        }

        return redirect('cpid');
    }

    public function store(){
        $data_ids = $_REQUEST['data_ids'];
        $data_id_array = explode(",", $data_ids);
        if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                DB::table('merchant_app_cp')->where('cpid', '=', $id)->delete();
            }
        }
    }
}
?>