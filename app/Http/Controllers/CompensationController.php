<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session,
    CommonHelper,
    Auth;
use DB,
    App\User,
    Kodeine\Acl\Models\Eloquent\Role;
use App\Models\Partner_info,
    App\Models\AppUser,
    App\Models\MerchantApp,
    App\Models\Compensation;
use App\Models\CashInfo;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompensationController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex() {
        $listpart =DB::table('partner_info')
            ->get();
        $part1 = 'SLG';
        //return view('compen.index');
        return view('/support/compensation_users',compact('listpart','part1'));
    }

    public function getEditCoin($id) {        
        $trans_info = Compensation::transactionInfo($id);
        $username = $trans_info[0]->name;
        $status = $trans_info[0]->payment_status;
        $trans_id = $trans_info[0]->trans_id;
        $uid = $trans_info[0]->uid;
        $coins = $trans_info[0]->coins;

        return view('support.editTransaction', compact('username', 'status', 'trans_id', 'uid', 'coins'));
    }

    public function getEdit($id) {
        $user = User::findOrFail($id);
        $data = Compensation::coinUser($id);
        $trans = Compensation::transactionUser($id);
        $coin = 0;
        if (isset($data[0]->coins)) {
            $coin = $data[0]->coins;
        }

        return view('support.viewInfoUser', compact('user', 'coin', 'trans'));
    }

    public function getSearch(Request $request) {
        $this->validate($request, [
            'keyword' => 'required|min:3|max:64'
        ]);

        $keyword = trim($request->get('keyword'));
        $namepart = trim($request->get('part'));
        $title = 'Kết quả tìm kiếm người dùng';

        $part1 = "";

        if(empty($namepart)){
            $part1 = 'SLG';
        }else{
            $part1 = $namepart;
        }
        if($namepart == 'SLG'){
            $users = User::where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
            if (is_numeric($keyword))
            {
                $users = User::where('id', $keyword)
                    ->orWhere('name', $keyword);
            }
            $users = $users->paginate(10);
        }else {
            $users = User::where('name', 'like', '%' . $keyword . '%')
                ->where('provider', $namepart)
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->where('provider', $namepart)
                ->orWhere('fid', $keyword);
            if (is_numeric($keyword))
            {
                $users = User::where('fid', $keyword)
                    ->where('provider',$namepart)
                    ->orWhere('name', $keyword)
                    ->where('provider',$namepart);
            }
            $users = $users->paginate(10);
        }
        $listpart =DB::table('partner_info')
            ->get();

        return view('/support/compensation_users', compact('users', 'title', 'keyword','part1','listpart'));
    }

    public function postUpdate(Request $request) {
        $admin = Auth::user()->name;
        $user = $request->username;
        $uid = $request->uid;
        $status = $request->status;
        $coins = $request->txtCoin;
        $amount = $coins * 100;
        $trans_id = $request->trans_id;
        $time = date('Y-m-d H:i:s', time());
        if(!is_numeric($coins)){
            return redirect()->back()->withErrors(['error[]' => 'Số coin không hợp lệ']);
        }
        $data1 = Compensation::updateTransaction($status, $amount, $trans_id, $coins);
        $added = CashInfo::incrementCoin($uid, $coins);
        //$data2 = Compensation::updateCashInfo($uid, $coins);
        $data3 = Compensation::insertLog($admin, $user, $amount, $coins, $trans_id, $time);
        Session::flash('flash_success', 'Cộng coins thành công.');
        return redirect()->back();
    }

}
