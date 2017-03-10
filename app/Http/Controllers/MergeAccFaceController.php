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
    App\Models\MergeAccFace,
    App\Models\MerchantApp,
    App\Models\Compensation;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MergeAccFaceController extends Controller {

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex() {
        //return view('compen.index');
        return view('mergeaccface.compensation_users');
    }

    public function getEditCoin($id) {
        $trans_info = Compensation::transactionInfo($id);
        $username = $trans_info[0]->name;
        $status = $trans_info[0]->payment_status;
        $trans_id = $trans_info[0]->trans_id;
        $uid = $trans_info[0]->uid;
        $coins = $trans_info[0]->coins;

        return view('mergeaccface.editTransaction', compact('username', 'status', 'trans_id', 'uid', 'coins'));
    }

    public function getEdit($id) {
        $user = User::findOrFail($id);
        $data = Compensation::coinUser($id);
        $trans = Compensation::transactionUser($id);
        $coin = 0;
        if (isset($data[0]->coins)) {
            $coin = $data[0]->coins;
        }

        return view('mergeaccface.viewInfoUser', compact('user', 'coin', 'trans'));
    }

    public function getSearch(Request $request) {
        $this->validate($request, [
            'keyword1' => 'required|min:3|max:64',
            'keyword2' => 'required|min:3|max:64'
        ]);

        $keyword1 = trim($request->get('keyword1'));
        $keyword2 = trim($request->get('keyword2'));

        $title = 'Kết quả tìm kiếm người dùng';

        $users1 = User::where('name', 'like', '%' . $keyword1 . '%')
                ->orWhere('email', 'like', '%' . $keyword1 . '%')
                ->orWhere('id', $keyword1)
                ->paginate(10);
        $users2 = User::where('name', 'like', '%' . $keyword2 . '%')
                ->orWhere('email', 'like', '%' . $keyword2 . '%')
                ->orWhere('id', $keyword2)
                ->paginate(10);
        return view('mergeaccface.compensation_users', compact('users1','users2', 'title', 'keyword'));
    }

    public function postUpdate(Request $request) {
        $input = $request->all();
        $idfrom = $input['idfrom'];
        $idto = $input['idto'];
        $user_from = User::Where('id', $idfrom)->first();
        
        $user_to = User::Where('id', $idto)->first();
        $move_arr = array(
            'fromid' => $user_from->id,
            'fromname' => $user_from->name,
            'fromemail' => $user_from->email,
            'toname' => $user_to->name,
            'toemail' => $user_to->email,
             'toid' =>  $user_to->id     
        );
        $to_arr = array(
            
        );
        $admin = Auth::user()->name;
        $user_from->name = $move_arr['toid'].'@'.$move_arr['toname'];
        $user_from->email = $move_arr['toid'].'@'.$move_arr['toemail'];
        $user_from->dataext = json_encode($move_arr);
        $user_from->save();
        $user_to->name = $move_arr['fromname'];
        $user_to->email = $move_arr['fromemail'];
        $user_to->dataext = json_encode($move_arr);
        $user_to->save();
        $time = date('Y-m-d H:i:s', time());
        $data1 = MergeAccFace::insertLog($move_arr['fromid'], $move_arr['toid'], $admin, json_encode($move_arr), $time);
        Session::flash('flash_success', 'Chuyển tài khoản thành công.');
        return redirect(route('merge-acc-face.loghistory'));
    }
    public function getLoghistory(){
         $logmerge = DB::table('log_merge_acc_face')->get();
//         print_r($logmerge);die;
        return view('mergeaccface.logmerge', compact('logmerge'));
    }
    public function getBack($id) {
        if (isset($id)) {
            $back_stt = MergeAccFace::BackMergeAcc($id);
            if ($back_stt > 0) {
                Session::flash('flash_success', 'Back  thành công.');
            } else {
                Session::flash('flash_error', 'Đã từng back ');
            }
            return redirect()->back();
        } else {

            Session::flash('flash_error', 'Không có id');
            return redirect()->back();
        }
    }

}
