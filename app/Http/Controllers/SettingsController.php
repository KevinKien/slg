<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request, Redis, Auth;
use Session, Response, Cache, Validator;

class SettingsController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */

    public function __construct()
    {
        if (!in_array(Auth::user()->name, ['caovuong', 'iphone', 'nbp85hn@gmail.com', 'tieumai93']))
        {
            redirect('/')->send();
        }
    }

    /**
     * Responds to requests to GET /settings
     */
    public function getIndex()
    {
        $request_log = (Cache::get('settings_request_log') == 1) ? 'checked' : '';

        $cyberpay = (Cache::get('settings_scratch_card_paygate_cyberpay', 1) == 1) ? 'checked' : '';

        $nganluong = (Cache::get('settings_paygate_nganluong', 0) == 1) ? 'checked' : '';

        $scratch_card_paygate = Cache::get('settings_scratch_card_paygate', 'NganLuong');

        $master_password = Cache::get('settings_master_password', ['password' => '', 'expired_at' => '']);

        return view('settings', compact('request_log', 'master_password', 'welcome_message', 'scratch_card_paygate', 'cyberpay', 'nganluong'));
    }

    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function postUpdate(Request $request)
    {
        $this->validate($request, [
            'paygate' => 'in:PayDirect,NganLuong',
            'card_seri' => 'min:8|max:15',
        ], [], [
            'paygate' => 'Cổng thanh toán thẻ cào',
        ]);

        $request->flash();

        $request_log = $request->has('request-log') ? 1 : 0;

        $cyberpay = $request->has('cyberpay') ? 1 : 0;

        $nganluong = $request->has('nganluong') ? 1 : 0;

        Cache::forever('settings_request_log', $request_log);

        Cache::forever('settings_scratch_card_paygate_cyberpay', $cyberpay);

        Cache::forever('settings_paygate_nganluong', $nganluong);

        Cache::forever('settings_scratch_card_paygate', $request->input('paygate'));

        Session::flash('flash_success', 'Lưu thành công.');

        return redirect()->back();
    }

    public function postAjaxGenerateMasterPassword(Request $request)
    {
        $result = ['password' => '', 'expired_at' => ''];

        if ($request->ajax() && Session::token() === $request->input('_token')) {
            $password = str_random(8);

            $expired_at = time() + 1800;

            Cache::put('settings_master_password', ['password' => $password, 'expired_at' => $expired_at], 30);

            $result = ['password' => $password, 'expired_at' => date('d/m/Y H:i:s', $expired_at)];
        }

        return Response::json($result);
    }

    public function clearRedisSession()
    {
        $redis = Redis::connection('session');

        $deleted = $redis->flushDb();

        if ($deleted) {
            Session::flash('flash_success', 'Xóa Session thành công.');
        } else {
            Session::flash('flash_error', 'Lỗi: không thể xóa được Session.');
        }

        return redirect()->back();
    }
}