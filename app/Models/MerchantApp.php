<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache,
    Response,
    Redis, DB;

class MerchantApp extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_app';

    const KEY_GAME_LIST = 'list_game_active';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
//    protected $hidden = ['password', 'remember_token'];
    public $timestamps = false;

    public static function setgamelist()
    {
        $keyGameList = self::KEY_GAME_LIST;
        $list_query = MerchantApp::where('status', 1)->whereNotNull('slug')->get();
        $GameList = array();
        foreach ($list_query as $key => $game) {
            $GameList[$key]['name'] = $game->name;
            $arr_img = explode(',', $game->images, -1);
            $GameList[$key]['img'] = $arr_img['0'];
            $arr_url = explode(',', $game->url, -1);
            $GameList[$key]['url'] = $arr_url['0'];
            $GameList[$key]['url_news'] = $game->url_news;
            $GameList[$key]['clientid'] = $game->clientid;
        }
        Cache::store('redis')->forever($keyGameList, $GameList);
    }

    public static function getGameList()
    {
        if (Cache::store('redis')->has(self::KEY_GAME_LIST)) {
            return (json_encode(Cache::store('redis')->get(self::KEY_GAME_LIST)));
        } else {
            MerchantApp::setgamelist();
            return (json_encode(Cache::store('redis')->get(self::KEY_GAME_LIST)));
        }
    }

    public static function getGameListApi()
    {
        if (Cache::store('redis')->has(self::KEY_GAME_LIST)) {
            return (Cache::store('redis')->get(self::KEY_GAME_LIST));
        } else {
            MerchantApp::setgamelist();
            return (Cache::store('redis')->get(self::KEY_GAME_LIST));
        }
    }

    public static function get_log_payment_zing()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid'])) {
            $date = date('y_m_d');
            $appid = '18903324';
            $key = 'Application_PAYMENT_ZING_' . $appid . '_' . $date;
            $data = Redis::get($key);

            //dd($data);
            //$data_log_redis = array();
            // if (!empty($data)) {
            $data_log_redis = json_decode($data);
            //}
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $appid = $_GET['appid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_PAYMENT_ZING_' . $appid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $data[] = Redis::get($value);

            }

            //print($data[1].'</br>');
            //chuyển mảng v�? chuỗi
            $d = json_encode($data);
            //hàm thay thế chuỗi
            $d1 = str_replace(']","[', ',', $d);
            //hàm cắt số kí tự ở đầu và cuối chuỗi
            $d2 = substr($d1, 2, -2);
            //loại b�? các ký tự \ trong chuỗi ký tự
            $d3 = stripslashes($d2);
            //print_r(explode('null,',$d3));
            $d4 = str_replace('ull,"', '', str_replace('null,', '', $d3));
            //print(str_replace('ull,"','',str_replace('null,','',$d3)));
            $d5 = str_replace('",nul', '', $d4);
            $str = '[{"appid":17054245,"uid":"326407","username":"dan_8679_804209012992591","amount":"serverid":"1","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_326407_55b9d055159cb","url_callback":"http:\/\/facebook.op.slg.vn\/home\/nap_tien_facebook","status_game":1,"telco":"VTT","serial":"92757609329","code":"0922457658165","time":1438240853}]","';
            $d6 = str_replace('' . $str . '', '', $d5);
            //print $d6;
            //print(str_replace('",nul','',$d4));
            // $data_log_redis = array();
            $data_log_redis = json_decode($d6);
            //}
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);

        return ($result);
        // return $data_log_redis;
    }

//    public static function get_total_amount_log_payment_zing() {
//        $result = [];
//        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid'])) {
//            $date = date('y_m_d');
//            $appid = '18903324';
//            $key = 'Application_PAYMENT_ZING_' . $appid . '_' . $date;
//            $data = Redis::get($key);
//            $json = json_decode(Redis::get($key));
//            $total = array();
//            $amount = 0;
//            $result = DB::table('merchant_app_cp')
//                ->select('cpid','cp_name','app_id')
//                ->where('partner_id', '=', 100000009)
//                ->where('app_id', '=', 18903324)
//                ->get();
//
//            if(empty($json)){}else{
//            foreach($json as $row){
//                $amount+=$row->amount;
//            }
//
//                $total[] = ['dt' => $amount, 'date' => $json[0]->time ,'cpid' => $result[0]->cpid ];
//            }
//
//
//            //dd($data);
//            //$data_log_redis = array();
//            // if (!empty($data)) {
//           // $data_log_redis = json_decode($data);
//            //}
//        } else {
//            $datefrom = date('Y-m-d', $_GET['dateform']);
//            $dateto = date('Y-m-d', $_GET['dateto']);
//            $appid = $_GET['appid'];
//            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
//            $key = array();
//            $result = DB::table('merchant_app_cp')
//                ->select('cpid','cp_name','app_id')
//                ->where('partner_id', '=', 100000009)
//                ->where('app_id', '=', $appid)
//                ->get();
//            for ($i = 0; $i < count($kq); $i++) {
//                $key[$i] = 'Application_PAYMENT_ZING_' . $appid . '_' . $kq[$i];
//            }
//            $data = array();
//            $total = array();
//            foreach ($key as $row => $value) {
//                $data[] = Redis::get($value);
//                $json = json_decode(Redis::get($value));
//                if(empty($json)){}else{
//                $amount = 0;
//                foreach($json as $row){
//                    $amount+=$row->amount;
//                }
//
//                $total[] = ['dt' => $amount, 'date' => $json[0]->time ,'cpid' => $result[0]->cpid];}
//            }
////            print_r($total);die;
//
//
//        }
//
//
//
//        return($total);
//        // return $data_log_redis;
//    }

    public static function get_log_payment_fpay1($request)
    {
        $result = [];
        $input = $request->all();
        $change = 2;
        $data_log_redis = "";
        if (!isset($input['dateform']) && !isset($input['dateto']) && !isset($input['cpid'])) {
            $date = date('y_m_d');
            $cpid = '300000125';
            $key = 'Application_REVENUE_' . $cpid . '_' . $date;

            if ($change == 1) {
                $data = Redis::get($key);
                $data_log_redis = json_decode($data);
            } else {
                $sub_data = LogRedis::where('key', $key)->select('id', 'key', 'cpid', 'value')->first();
                if (!empty($sub_data)) {
                    $data_log_redis = json_decode($sub_data->value);
                }
            }
        } else {

            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            //$cpid = $_GET['cpid'];
            switch ($_GET['cpid']) {
                case '300000129':
                    $cpid = 'vqc';
                    break;
                default:
                    $cpid = $_GET['cpid'];
                    break;
            }
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_REVENUE_' . $cpid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $a = "";
                $b = "";
                if ($change == 1) {
                    $a = Redis::get($value);
                    $b = json_decode($a, true);
                } else {
                    $a = LogRedis::where('key', $value)->select('id', 'key', 'cpid', 'value')->first();
                    if (!empty($a)) {
                        $b = json_decode($a->value, true);
                    }
                    // print_r($data_value);die;
                }
                if (is_array($b)) {
                    foreach ($b as $k => $b1) {
                        if ($b1 == 1) unset ($b[$k]);
                        if (!isset($b[$k]['amount']) || $b[$k]['uid'] == '') {
                            unset ($b[$k]);
                        } elseif (isset ($input['uid'])) {
                            if ($b[$k]['uid'] == $input['uid']) {
                                $data[] = $b[$k];
                            } else {
                                unset ($b[$k]);
                            }

                        } else {
                            $data[] = $b[$k];
                        }
                    }
                }
            }
            $d = json_encode($data);
            $data_log_redis = json_decode($d);
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
//                if(isset($_GET['cpid'])&&$_GET['cpid']=='300000000'){
//                    $result[strtotime($value->time) . '-' . $value->uid] = $value;
//                }else
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);
        return ($result);
    }

    public static function get_Account_test_fpay()
    {
        $result = DB::table('testid')
            ->get();
        return $result;
    }

    public static function get_log_payment_fpay()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid']) && !isset($_GET['type'])) {
            $date = date('y_m_d');
            $type = 'TELCO';
            $appid = '17054245';
            $key = 'Application_PAYMENT_' . $type . '_' . $appid . '_' . $date;
            $data = Redis::get($key);

            $data_log_redis = json_decode($data);
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $type = $_GET['type'];
            $appid = $_GET['appid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_PAYMENT_' . $type . '_' . $appid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $data[] = Redis::get($value);
            }
            //print($data[1].'</br>');
            //chuyển mảng v�? chuỗi
            $d = json_encode($data);
            //hàm thay thế chuỗi
            $d1 = str_replace(']","[', ',', $d);
            //hàm cắt số kí tự ở đầu và cuối chuỗi
            $d2 = substr($d1, 2, -2);
            //loại b�? các ký tự \ trong chuỗi ký tự
            $d3 = stripslashes($d2);
            //print_r(explode('null,',$d3));
            $d4 = str_replace('ull,"', '', str_replace('null,', '', $d3));
            //print(str_replace('ull,"','',str_replace('null,','',$d3)));
            $d5 = str_replace('",nul', '', $d4);
            $d6 = str_replace('cout":0', 'cout":"0"', $d5);
            $str = '{"appid":"17054245","uid":"346114","username":"amount":100,"serverid":"47","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f12eded02","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":2,"telco":"VT","serial":"92757609329","code":"0922457658165","time":1438249263,"cout":"0"},{"appid":"17054245","uid":"346114","username":"amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f143b81b3","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":2,"telco":"VT","serial":"92757609329","code":"0922457658165","time":1438249283,"cout":"0"},{"appid":"17054245","uid":"346114","username":"01676563279","amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f1f107fe5","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":1,"telco":"VT","serial":"92757609329","code":"0922457658165","time":1438249462,"cout":"2747335"},{"appid":"17054245","uid":"346114","username":"01676563279","amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f318eb2c7","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":1,"telco":"VT","serial":"92757609330","code":"0922457658166","time":1438249753,"cout":"2747355"},{"appid":"17054245","uid":"346114","username":"01676563279","amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f37f78c40","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":1,"telco":"VT","serial":"92757609330","code":"0922457658166","time":1438249855,"cout":"2747367"},{"appid":"17054245","uid":"346114","username":"01676563279","amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9f64ba9271","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":1,"telco":"VT","serial":"92757609330","code":"0922457658166","time":1438250572,"cout":"2747387"},{"appid":"17054245","uid":"346114","username":"01676563279","amount":100,"serverid":"46","token":"B93D4BE8-C3D0-A944-9E22-16C68195B496","request_id":"00711_346114_55b9fa35bc48b","url_callback":"http:\/\/op.slg.vn\/home\/nap_tien","status_game":1,"telco":"VT","serial":"92757609330","code":"0922457658166","time":1438251574,"cout":"2747527"}';
            $d7 = str_replace('' . $str . ',', '', $d6);
            $d8 = str_replace('code":"time', 'code":"","time', $d7);
            $d9 = str_replace(']","[', ',', $d8);
            //print($d9);
            $data_log_redis = json_decode($d9);
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);

        return ($result);
    }

    public static function get_log_payment_soha()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid'])) {
            $date = date('y_m_d');
            $appid = '18903324';
            $key = 'Application_PAYMENT_SH_' . $appid . '_' . $date;
            $data = Redis::get($key);

            //$data_log_redis = array();
            //if (!empty($data)) {
            $data_log_redis = json_decode($data);
            //}
        } else {

            if ($_GET['appid'] == '300000193') {
                $datefrom = date('Y-m-d', $_GET['dateform']);
                $dateto = date('Y-m-d', $_GET['dateto']);
                $appid = $_GET['appid'];
                $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
                $key = array();
                for ($i = 0; $i < count($kq); $i++) {
                    $key[$i] = 'Application_REVENUE_300000193_' . $kq[$i];
                }
                $data = array();
                foreach ($key as $row => $value) {
                    $shval = DB::table('log_redis')
                        ->select('value')
                        ->where('key', $value)
                        ->first();
                    if (!empty($shval)) {
                        $b = json_decode($shval->value, true);
                    }
                    if (is_array($b)) {
                        foreach ($b as $k => $b1) {
                            if ($b1 == 1) unset ($b[$k]);
                            if (!isset($b[$k]['amount']) || $b[$k]['uid'] == '') {
                                unset ($b[$k]);
                            } elseif (isset ($input['uid'])) {
                                if ($b[$k]['uid'] == $input['uid']) {
                                    $data[] = $b[$k];
                                } else {
                                    unset ($b[$k]);
                                }

                            } else {
                                $data[] = $b[$k];
                            }
                        }
                    }
                }

                $d = json_encode($data);
                $data_log_redis = json_decode($d);
            } else {

                $datefrom = date('Y-m-d', $_GET['dateform']);
                $dateto = date('Y-m-d', $_GET['dateto']);
                $appid = $_GET['appid'];
                $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
                $key = array();
                for ($i = 0; $i < count($kq); $i++) {
                    $key[$i] = 'Application_PAYMENT_SH_' . $appid . '_' . $kq[$i];
                }
                $data = array();
                foreach ($key as $row => $value) {
                    $shval = Redis::get($value);
                    if (isset($shval)) {
                        $data[] = $shval;
                    }

                }
                //print($data[1].'</br>');
                //chuyá»ƒn máº£ng vá»� chuá»—i
                $d = json_encode($data);
                //hÃ m thay tháº¿ chuá»—i
                $d1 = str_replace(']","[', ',', $d);
                //hÃ m cáº¯t sá»‘ kÃ­ tá»± á»Ÿ Ä‘áº§u vÃ  cuá»‘i chuá»—i
                $d2 = substr($d1, 2, -2);
                //loáº¡i bá»� cÃ¡c kÃ½ tá»± \ trong chuá»—i kÃ½ tá»±
                $d3 = stripslashes($d2);
                //print_r(explode('null,',$d3));
                $d4 = str_replace('ull,"', '', str_replace('null,', '', $d3));
                //print(str_replace('ull,"','',str_replace('null,','',$d3)));
                $d5 = str_replace('",nul', '', $d4);
                $str = '{"appid":17054245,"uid":"soha824578364","amount":120000,"serverid":"20","status_game":"settled","time":1438672056},';
                $d6 = str_replace('' . $str . '', '', $d5);
                //print $d6;
                //print(str_replace('",nul','',$d4));
                // $data_log_redis = array();
                //if (!empty($data)) {
                $data_log_redis = json_decode($d6);
                //}

            }
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);
        return ($result);
        //return($data_log_redis);
    }

    public static function get_log_mwork()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['cpid'])) {
            $date = date('y_m_d');
            $cpid = '300000001';
            $key = 'Application_REVENUE_' . $cpid . '_' . $date;
            //  $a = Redis::get($key);
//              $b = json_decode($a, true);
//                if(is_array($b)){
//                foreach ($b as $k => $b1){
//                    if($b1==1) unset ($b[$k]);
//                    if(!isset($b[$k]['amount'])||$b[$k]['uid']==''){
//                        unset ($b[$k]);
//                    }
//                    else{
//                        $data = $b[$k];
//                    }
//                }
//                }
//            $d = json_encode($data);
            $data = Redis::get($key);
            $data_log_redis = json_decode(str_replace('1,', '', $data));
            //$data_log_redis = json_decode($d);
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $cpid = $_GET['cpid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_REVENUE_' . $cpid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $a = Redis::get($value);
                //print $a;die;
                $b = json_decode($a, true);
                if (is_array($b)) {
                    foreach ($b as $k => $b1) {
                        if ($b1 == 1) unset ($b[$k]);
                        if (!isset($b[$k]['amount']) || $b[$k]['uid'] == '') {
                            unset ($b[$k]);
                        } else {
                            $data[] = $b[$k];
                        }
                    }
                }
            }
            $d = json_encode($data);
            $data_log_redis = json_decode($d);
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $time = strtotime($value->time);
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);

        return ($result);
        //return($data_log_redis);
    }

    public static function get_log_mwork_topup()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['cpid'])) {
            $date = date('y_m_d');
            $cpid = '300000001';
            $key = 'Application_TOPUP_' . $cpid . '_' . $date;
            $data = Redis::get($key);
            $data_log_redis = json_decode(str_replace('1,', '', $data));
            //$data_log_redis = json_decode($d);
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $cpid = $_GET['cpid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_TOPUP_' . $cpid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $a = Redis::get($value);
                //print $a;die;
                $b = json_decode($a, true);
                if (is_array($b)) {
                    foreach ($b as $k => $b1) {
                        if ($b1 == 1) unset ($b[$k]);
                        if (!isset($b[$k]['amount']) || $b[$k]['uid'] == '') {
                            unset ($b[$k]);
                        } else {
                            $data[] = $b[$k];
                        }
                    }
                }
            }
            $d = json_encode($data);
            $data_log_redis = json_decode($d);
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $time = strtotime($value->time);
                $result[$value->time . '-' . $value->uid] = $value;
            }
        }
        krsort($result);

        return ($result);
        //return($data_log_redis);
    }

    public static function get_log_payment_facebook()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid'])) {
            $date = date('y_m_d');
            $appid = '17054245';
            $key = 'Application_PAYMENT_FACEBOOK_' . $appid . '_' . $date;
            $data = Redis::get($key);
            // $data_log_redis = array();
            //if (!empty($data)) {
            $data_log_redis = json_decode($data);
            // }
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $appid = $_GET['appid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_PAYMENT_FACEBOOK_' . $appid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $data[] = Redis::get($value);
            }
            $d = json_encode($data);
            //hÃ m thay tháº¿ chuá»—i
            $d1 = str_replace(']","[', ',', $d);
            $d2 = substr($d1, 2, -2);
            $d3 = stripslashes($d2);
            $d4 = str_replace(',null', '', $d3);
            $d5 = str_replace('",nul', '', $d4);
            $d6 = str_replace('null', '0', $d5);
            $d7 = str_replace('ull,"', '', $d6);
            $d8 = str_replace(']","[', ',', $d7);
            //print($d8);
            //$data_log_redis = array();
            // if (!empty($data)) {
            $data_log_redis = json_decode($d8);
            //}
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $result[$value->time . '-' . $value->code] = $value;
            }
        }
        krsort($result);

        return ($result);
        // return($data_log_redis);
    }

    public static function get_log_payment_garena()
    {
        $result = [];
        if (!isset($_GET['dateform']) && !isset($_GET['dateto']) && !isset($_GET['appid'])) {
            $date = date('y_m_d');
            $appid = '17054245';
            $key = 'Application_PAYMENT_GARENA_' . $appid . '_' . $date;
            $data = Redis::get($key);

            // $data_log_redis = array();
            // if (!empty($data)) {
            $data_log_redis = json_decode($data);
            // }
        } else {
            $datefrom = date('Y-m-d', $_GET['dateform']);
            $dateto = date('Y-m-d', $_GET['dateto']);
            $appid = $_GET['appid'];
            $kq = self::createDateRangeArray('' . $datefrom . '', '' . $dateto . '');
            $key = array();
            for ($i = 0; $i < count($kq); $i++) {
                $key[$i] = 'Application_PAYMENT_GARENA_' . $appid . '_' . $kq[$i];
            }
            $data = array();
            foreach ($key as $row => $value) {
                $data[] = Redis::get($value);
            }
            $d = json_encode($data);
            //hÃ m thay tháº¿ chuá»—i
            $d1 = str_replace(']","[', ',', $d);
            //hÃ m cáº¯t sá»‘ kÃ­ tá»± á»Ÿ Ä‘áº§u vÃ  cuá»‘i chuá»—i
            $d2 = substr($d1, 2, -2);
            //loáº¡i bá»� cÃ¡c kÃ½ tá»± \ trong chuá»—i kÃ½ tá»±
            $d3 = stripslashes($d2);
            $d4 = str_replace(',null', '', $d3);
            $d5 = str_replace('",nul', '', $d4);
            $d6 = str_replace('null', '0', $d5);
            $d7 = str_replace('ull,"', '', $d6);
            $d8 = str_replace(']","[', ',', $d7);
            //print($d8);
            //$data_log_redis = array();
            // if (!empty($data)) {
            $data_log_redis = json_decode($d8);
            // }
        }
        if (is_array($data_log_redis)) {
            foreach ($data_log_redis as $key => $value) {
                $result[$value->time . '-' . $value->code] = $value;
            }
        }
        krsort($result);

        return ($result);
        //return($data_log_redis);
    }

    public static function getMerchantAppById($id)
    {
        //create Cache
        $cache_key = 'merchanApp_ById' . $id;
        $time = 10 * 60;

        //get from DB
        if (!Cache::has($cache_key)) {
            $data = DB::table('merchant_app')->where('id', '!=', $id)->
            get();

            //set to Cache
            $value = Cache::add($cache_key, $data, $time);
        }
        //get from Cache
        $obj = Cache::get($cache_key);
        return $obj;
    }

    public static function getKeyDbLogRedisbydate()
    {        
        $result = [];
        if (!isset($_GET['datefrom']) && !isset($_GET['cpid'])) {
            
            $date = date('Y-m-d') . '%';
            $appid = '1001';           
            $result = DB::table('log_coin_transfer')
                ->select(DB::raw('coin as amount'), DB::raw('user_id as uid'), DB::raw('request_time as time'),'response')
                ->where('status', '1')
                ->where('request_time', 'like', $date)
                ->where('app_id', $appid)
                ->orderBy('request_time', 'DESC')
                ->get();
        
            //$data_log_redis = json_decode($d);
        } else {
           
            $partner = Merchant_app_cp::getLogpartner();
           
            if ($partner == 'Fpay') {
               
                $result = LogCoinTransfer::getLogFpay();
            } elseif ($partner == 'Zing') {
                
                $result = LogBuyItemZing::getLogZing();
            } else {
               
                $result = LogBuyItemSoha::getLogSoha();
            }
        }


        krsort($result);
        
        return ($result);
        //return($data_log_redis);
    }

    public static function createDateRangeArray($strDateFrom, $strDateTo)
    {
        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('y_m_d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('y_m_d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public static function setCache()
    {
        $games = self::where('status', 1)->get();
        Cache::forever('active_game_list', $games->toArray());

        $apps = [];
        foreach ($games as $game)
        {
            $apps[$game->clientid] = $game->id;
            $apps[$game->slug] = $game->id;
        }

        Cache::forever('active_app_list', $apps);
    }
}
