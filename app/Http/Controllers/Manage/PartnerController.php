<?php

namespace App\Http\Controllers\Manage;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CheckPartnerRequest;
use App\Models\Partner_info;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Session, App\User, Kodeine\Acl\Models\Eloquent\Role;
class PartnerController extends Controller
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
        return redirect('partner/index');
    }
    public function getIndex()
    {
        $partner_total=Partner_info::All();
        $partner=DB::table('partner_info')
                ->paginate(10);
       // $partner = Partner_info::All()->paginate(2);
        return view('/partner/list_partner', compact('partner','partner_total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function getSearch(Request $request)
    {
        $name = trim($request->get('searchname'));
//            print_r($game);die;
        $partner_total=Partner_info::All();
        $page = 1;
        if(!isset($_GET['page'])){

        }else{
            $page=$_GET['page'];
        }
        $partner = '';
        if($name == ''){
            $partner = DB::table('partner_info')
                ->paginate(10);
        }else{
            $partner = DB::table('partner_info')
                ->where('partner_name','like' ,$name.'%')
                ->paginate(10);}
//        print_r($game);die;
        //->get();
        //print_r($results);die;
        return view('/partner/list_partner', compact('partner','partner_total','page'));
    }

    public function getAdd()
    {
       return view('/partner/add_partner');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postIndex(CheckPartnerRequest $request)
    {
        $dulieu_tu_input = $request->all();
        $request->flashOnly('partner-name', 'payment-url-callback');
        $partner = new Partner_info;
        //L?y th�ng tin t? c�c input dua v�o
        //trong model Merchant_app_cp
        $partner->partner_name = $dulieu_tu_input["partner-name"];
        $partner->payment_url_callback = $dulieu_tu_input["payment-url-callback"];
        //Ti?n h�nh luu d? li?u v�o database
        $partner->save();
        Session::flash('flash_success', 'The partner added successfully.');
        return redirect('partner');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit()
    {
        $partnerid=$_GET['partnerid'];
        $partner=DB::table('partner_info')->where('partner_id','=',$partnerid)->get();
        return view('/partner/edit_partner', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postEdit(CheckPartnerRequest $request)
    {
       $partnerid=$_GET['partnerid'];
        $dulieu_tu_input = $request->all();
        $request->flashOnly('partner-name', 'payment-url-callback');
        $partner = new Partner_info;
        DB::table('partner_info')
            ->where('partner_id', $partnerid)
            ->update(['partner_name' => $dulieu_tu_input["partner-name"],
                        'payment_url_callback' => $dulieu_tu_input["payment-url-callback"]]);
        Session::flash('flash_success', 'The partner updated successfully.');
        return redirect('partner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
     public function getDelete()
    {
        $partnerid = $_GET['partnerid'];
        DB::table('partner_info')->where('partner_id', '=', $partnerid)->delete();
        Session::flash('flash_success', 'The partner deleted successfully.');
        return redirect('partner');
    }

    public function store(){
        $data_ids = $_REQUEST['data_ids'];
        $data_id_array = explode(",", $data_ids);
        if(!empty($data_id_array)) {
            foreach($data_id_array as $id) {
                DB::table('partner_info')->where('partner_id', '=', $id)->delete();
            }
        }
    }
}
