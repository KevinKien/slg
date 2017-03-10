<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request,
    Response,
    Validator;
use App\Models\Dau_log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\MerchantApp;
use App\Models\AppInfor;
use Illuminate\Support\Facades\Redis;
use DB;
use Carbon\Carbon;
use GameHelper;

//echo Carbon::now();

class CheckApprovalController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function check_approval(Request $request) {
        // truyền lên client_id & os_id        
        // validate request
        $messages = [
            'client_id.required' => CLIENT_ID_REQUITE,
            'client_id.exists' => CLIENT_ID_FALSE,
        ];
        $validator = Validator::make($request->all(), [
                    'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
                    'os_id' => 'integer|min:1',
                        ], $messages);


        $client_id = $request->input('client_id');
        $sdk_version = $request->input('sdk_version');
        // fix client for test sdk => soul
        if ($client_id == '123456789') {
            $client_id = '9194439505';
        }

        $check_version = GameHelper::isInReview($client_id, $sdk_version);
        $result = 0;

        if ($check_version == true) {
            $result = 1;
        }

        $data = array();
        $data['result'] = $result;
        $status = 200;
        $data_array = array();
        $data_array['data'] = $data;
        $data_array['error_code'] = $status;

        return Response::json($data_array);
    }

    public function homepage(Request $request) {
        //@todo validate $request
        // validate request
        $messages = [
            'client_id.required' => CLIENT_ID_REQUITE,
        ];
        $validator = Validator::make($request->all(), [
                    'client_id' => 'required',
                        ], $messages);


        $client_id = $request->input('client_id');
        // fix client for test sdk => soul
        if ($client_id == '123456789') {
            $client_id = '9194439505';
        }

        $model_appinfor = AppInfor::list_appinfor($client_id);
        if (!(is_array($model_appinfor) && count($model_appinfor))) {
            // client_id not found
            dd('false');
            return;
        }

        $data = array();
        $data['url_hompage'] = $model_appinfor[0]->url_homepage;
        $data['url_fanpage'] = $model_appinfor[0]->fanpage;
        $data['url_huongdan'] = $model_appinfor[0]->guide;

        $data['ga'] = $model_appinfor[0]->ga;
        $data['gc'] = $model_appinfor[0]->gc;

        //poupou fix
        if ($client_id == '8633283045') {
            $data['ios_AppStore_id'] = '1107919627';
        } else {
            $data['ios_AppStore_id'] = '1053157907';
        }
        $data['appsflyer_android'] = 'tN3bNhAgfB5Bf3hwCiMAtM';
        $data['appsflyer_ios'] = 'tN3bNhAgfB5Bf3hwCiMAtM';

        // msg default
        $data['msg_default'] = $this->getMsgDefault($client_id);
        // banner default
        $data['popup'] = $this->getPopupDefault($client_id);

        $status = 200;
        $data_array = array();
        $data_array['data'] = $data;
        $data_array['error_code'] = $status;
        return Response::json($data_array);
    }

    private function getMsgDefault($client_id) {
        // fix
        $msg = array();
        if (!empty($client_id)) {
            switch ($client_id) {
                case '9194439505':
                    $msg[] = array(
                        'time' => mktime(date("H"), date("i"), date("s") + 5, date("n"), date("j"), date("Y")),
                        'msg' => 'msg 5s' // 5s - hour, minute, second, month, day, year
                    );
                    $msg[] = array(
                        'time' => mktime(date("H"), date("i"), date("s") + 15, date("n"), date("j"), date("Y")),
                        'msg' => 'msg 15s' // 5s - hour, minute, second, month, day, year
                    );
                    $msg[] = array(
                        'time' => mktime(date("H"), date("i"), date("s") + 25, date("n"), date("j"), date("Y")),
                        'msg' => 'msg 25s' // 5s - hour, minute, second, month, day, year
                    );
                    break;
            }
        }

        return $msg;
    }

    private function getPopupDefault($client_id) {
        // fix
        $msg = array();
        if (!empty($client_id)) {
            switch ($client_id) {
                case '9194439505':
                    $msg[] = array(
                        'img_url_phone' => 'http://store-slg.cdn.vccloud.vn/thantien%C4%91ao/IMG_0141.PNG',
                        'img_url_tablet' => 'http://store-slg.cdn.vccloud.vn/thantien%C4%91ao/IMG_0141.PNG',
                        'ios_link_iphone' => 'itms-services://?action=download-manifest&url=https://slg6.cdn.vccloud.vn/thienlong/sdk/ios/soul.plist',
                        'ios_link_ipad' => 'https://itunes.apple.com/vn/app/than-tien-ao/id1053157907?mt=8',
                        'android_link_phone' => 'https://play.google.com/store/apps/details?id=com.xiazoo.soul.yuenan',
                        'android_link_tablet' => 'https://play.google.com/store/apps/details?id=com.xiazoo.soul.yuenan',
                    );
                    break;
                case '8633283045':
                    $msg[] = array(
                        'img_url_phone' => 'http://store-slg.cdn.vccloud.vn/thantien%C4%91ao/IMG_0141.PNG',
                        'img_url_tablet' => 'http://store-slg.cdn.vccloud.vn/thantien%C4%91ao/IMG_0141.PNG',
                        'ios_link_iphone' => 'https://itunes.apple.com/us/app/tieu-long/id1107919627?ls=1&mt=8',
                        'ios_link_ipad' => 'https://itunes.apple.com/us/app/tieu-long/id1107919627?ls=1&mt=8',
                        'android_link_phone' => 'https://play.google.com/store/apps/details?id=com.ssg.fishparty.soloGame',
                        'android_link_tablet' => 'https://play.google.com/store/apps/details?id=com.ssg.fishparty.soloGame',
                    );
                    break;
            }
        }

        return $msg;
    }

}
