<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\Games\GameServices;
use App\Helpers\CommonHelper;
use Auth, cURL,
    Validator;
use App\Models\CashInfo;
use Illuminate\Http\Request;
use App\Models\MerchantApp;
use App\Models\Merchant_app_cp;
use App\Http\Controllers\Controller;
use App\Helpers\Logs\RevenueLogHelper;

class TopCoinController extends Controller {

    private static $coins = [100, 200, 300, 500, 1000, 2000, 3000, 5000, 10000, 30000];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $games = MerchantApp::where('status', 1)->whereNotNull('slug')->where('topcoin', 1)->get();
        return view('frontend.topcoin-step1', ['games' => $games]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $slug
     * @return Response
     */
    public function game($slug) {
        $game = MerchantApp::where('slug', trim($slug))->where('status', 1)->first();

        if (!$game)
        {
            abort(404);
        }

        $service = GameServices::createService($game->id);

        if (is_null($service)) {
            abort(404);
        }

        $servers = GameServices::getServerList($game->id);

        $coins = self::$coins;

        return view('frontend.topcoin-step2', compact('game', 'servers', 'coins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param $slug
     * @return Response
     */
    public function transfer(Request $request, $slug) {
        $user = Auth::user();

        if (!isset($user->id) && empty($user->id)) {
            abort(404);
        }

        $game = MerchantApp::where('slug', trim($slug))->where('status', 1)->first();

        if (!$game)
        {
            abort(404);
        }
//
//		if (!$request->has('g-recaptcha-response')) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }
		
//		$ip = CommonHelper::getClientIP();

//        try {
//            $verify = cURL::post('https://www.google.com/recaptcha/api/siteverify', [
//                        'secret' => '6LfDGBMTAAAAAILSMorVcznkh8XiCX6AMuuerUzQ',
//                        'response' => $request->input('g-recaptcha-response'),
//                        'remoteip' => $ip,
//            ]);
//        } catch (\Exception $e) {
//            $request->session()->flash('flash_error', 'Lỗi khi gọi chứng thực Captcha, vui lòng thử lại.');
//            return redirect()->back()->withInput();
//        }
//
//        $captcha_response = json_decode($verify->body, true);
//
//        if (!isset($captcha_response['success']) || $captcha_response['success'] === false) {
//            $request->session()->flash('flash_error', 'Vui lòng xác thực bạn là con người, không phải máy.');
//            return redirect()->back()->withInput();
//        }

        $cash = CashInfo::where('uid', $user->id)->first();

        $current_coin = empty($cash) ? 0 : $cash->coins;

        $rules = [
            'server' => 'required',
            'coin' => 'required|in:' . implode(',', self::$coins)
        ];

        $validator = Validator::make($request->all(), $rules, [],[
            'server' => 'Máy chủ',
            'coin' => 'Coin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $coin = $request->input('coin');

        if (empty($cash) || $current_coin < $coin) {
            $request->session()->flash('flash_error', 'Không đủ Coin để nạp.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service = GameServices::createService($game->id);

        if (is_null($service)) {
            $request->session()->flash('flash_error', 'Không thể kết nối dến dịch vụ.');
            return redirect()->back();
        }

        $r_uid = $user->id;

        // LinhVuong hoac OP thi check tai khoan fpay
        if ($game->id == 1001 || $game->id == 17054245)
        {
            // uid from partner
            $r_uid = $this->getRealUid($user);
        }
        
        $server_id = $request->input('server');

        $result = $service->transfer($r_uid, $server_id, $r_uid . time(), $coin);

        if ($result === false) {
            $request->session()->flash('flash_error', 'Người chơi không tồn tại trên máy chủ.');
        } elseif ($result == 'success') {
            $request->session()->flash('flash_success', 'Nạp Coin thành công.');
            CashInfo::decrementCoin($user->id, $coin);
//            *************ghi log**********************
            $cpid = Merchant_app_cp::getCpidUser($user->provider, $game->id);
            $arr = [
                'cpid' => $cpid,
                'uid' => $user->id,
                'telco' => "1",
                'clientid' => $game->client_id,
                'serial' => "1",
                'amount' => $coin * 100,
                'code' => "1",
            ];
            $revenuelog = new RevenueLogHelper;
            $revenuelog->setRevenue($arr);
//            *************ghi log**********************
            
            // todo log to revenue game
        } else {
            $request->session()->flash('flash_error', 'Lỗi hệ thống, vui lòng thử lại.');
        }

        return redirect()->back()->withErrors($validator)->withInput();
    }

    private function getRealUid($user) {
        if (is_object($user)) {

            switch ($user->provider) {
                case 'fpay':
                    $id = $user->fid;
                    break;
                default :
                    $id = 'slg' . $user->id;
                    break;
            }

            return $id;
        }
        return 'slg' . $user->id;
    }

}
