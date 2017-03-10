<?php

namespace App\Http\Controllers;

use App\Models\Partner_info, App\Models\AppUser, App\Models\MerchantApp;
use DB, Session,Validator,Response, App\User, Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class UserController extends Controller
{
    /**
     * Responds to requests to GET /users
     */
    public function getIndex()
    {
        $listpart =DB::table('partner_info')
            ->get();
        $part1 = 'SLG';
        return view('user.index', compact('listpart','part1'));
    }

    /**
     * Responds to requests to GET /users/edit/1
     * @param $id
     * @return Response
     */
    public function getEdit($id, $page=1)
    {
        $user = User::findOrFail($id);
        $user_apps = AppUser::where('user_id', $id)->get();
        $partners = Partner_info::all();
        $apps = MerchantApp::all();
        $roles = Role::all();
        
        $users1 = DB::table('users')
            ->leftJoin('cash_info', 'users.id', '=', 'cash_info.uid')
            ->select('users.id', 'users.fullname', 'users.created_at', 'users.provider', 'users.provider_id', 'users.active', 'cash_info.coins', 'cash_info.point')
            ->where('users.id', '=', $id)
            ->first();
        $log = DB::table('log_charge_coin_telco')                
            ->orderBy('created_at', 'desc')      
            ->take(10)
            ->select('log_charge_coin_telco.id', 'log_charge_coin_telco.trans_id', 'log_charge_coin_telco.created_at', 'log_charge_coin_telco.card_code',
                'log_charge_coin_telco.card_seri', 'log_charge_coin_telco.coin', 'log_charge_coin_telco.amount', 'log_charge_coin_telco.payment_status'
                , 'log_charge_coin_telco.card_type')
            ->where('log_charge_coin_telco.uid', '=', $id)
            ->get();                                
        
        return view('user.edit', compact('user', 'roles', 'user_apps', 'partners', 'apps', 'users1', 'log'));
    }

    /**
     * Update the specified user.
     *
     * @param  Request $request
     * @param $id
     * @return Response
     */
    public function postUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validation = [
            'name' => 'required|min:6|unique:users,name,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'numeric',
            'identify' => 'numeric',
            'password' => 'min:6|max:255|confirmed',

        ];

        if ($request->input('role') == 'partner') {
            $validation['partner_id'] = 'required';
            $request->merge(['app_id' => null]);
        } elseif ($request->input('role') == 'deploy') {
            $validation['app_id'] = 'required';
            $request->merge(['partner_id' => null]);
        } else {
            $request->merge(['partner_id' => null]);
            $request->merge(['app_id' => null]);
        }

        $data = $request->all();

        $this->validate($request, $validation);
        
        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
            unset($data['password_confirmation']);
        }

        $user->fill($data)->save();

        if ($request->input('role') !== '') {
            $user->syncRoles($request->input('role'));
        } else {
            $user->revokeAllRoles();
        }

        $active = $request->has('active') ? 1 : 0;
        $user->active = $active;
        $user->save();

        Session::flash('flash_success', 'The user updated successfully.');

        return redirect()->back();
    }

    /**
     * Responds to requests to GET /search/keyword
     * @param Request $request
     * @return Response
     */
    public function getSearch(Request $request)
    {
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
        $users = '';
        if($namepart == 'SLG'){
            $namepart = '';
            $users = User::where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');

            if (is_numeric($keyword))
            {
                $users = User::where('id', $keyword)
                    ->orWhere('name', $keyword);
            }
            
            $users = $users->paginate(10);
        }else{
            $users = User::where('name', 'like', '%' . $keyword . '%')
                ->where('provider',$namepart)
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->where('provider',$namepart);
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
        return view('user.index', compact('users','listpart','part1', 'title', 'keyword'));
    }

    public function getFix()
    {
        return view('user.fix');
    }

    public function postFix(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:64'
        ]);

        $name = trim($request->input('name'));

        $users = User::where('name', $name)
            ->whereNull('provider')
            ->whereNull('fid')
            ->first();

        if ($users) {
            $user_fpay = DB::table('users_fpay')->where('name', $name)->first();

            if (!$user_fpay) {
                $request->session()->flash('flash_error', 'Người dùng không tồn tại trên FPAY.');
                return redirect()->back()->withInput();
            }

            $data = ['provider' => 'fpay', 'fid' => $user_fpay->uid];

            if ($request->has('replace_password')) {
                $data['password'] = $user_fpay->pass;
            }

            User::where('name', $name)->update($data);

            $request->session()->flash('flash_success', 'Ghép thành công.');
        } else {
            $request->session()->flash('flash_error', 'Người dùng không hợp lệ (provider hoặc fid khác NULL).');
        }

        return redirect()->back()->withInput();
    }
}