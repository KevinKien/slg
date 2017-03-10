<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\LogCoinTransfer;
use App\Http\Controllers\Controller;
use App\Helpers\Games\GameServices;
use App\Models\MerchantAppProductApple;
use Illuminate\Http\Request,
    Route,
    Response,
    Validator;
use cURL,
    Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use ReceiptValidator\iTunes\Validator as iTunesValidator;
use App\Helpers\Logs\RevenueLogHelper;
use App\Helpers\Logs\UtilHelper;

class AppleService1Controller extends Controller {

    public function __construct(Request $request) {
        $this->middleware('oauth', ['only' => ['getReceipt']]);
    }

    public function getTest() {
        dd('test');
    }

    public function getReceipt(Request $request) {
        $arr_log = array();
        $arr_log['request_time'] = date('Y-m-d H:i:s');

        //check userinfo from access_token
        $token = Input::get('access_token');
        $client_id = Input::get('client_id');
        $server_id = Input::get('server_id');
        $amount = Input::get('amount');
        $receipt = Input::get('receipt');
        $money_in_game = Input::get('money_in_game');

        // validate request
        $validator = Validator::make($request->all(), [
                    'receipt' => 'required',
                    'access_token' => 'required',
                    'refresh_token' => 'required',
                    'client_id' => 'required',
                    'server_id' => 'required',
                    'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            //return Response::json($errors);
        }

        // step _ 1
//        $cache_key = 'log_step1';
//        $time = 60 * 60;
//        Redis::set($cache_key, ($receipt));

        //
        // valid receipt
        $receipt_info = $this->validReceipt($receipt);
        if (!is_object($receipt_info) || !$receipt_info->isValid()) {
            // response
            $result = array();
            $result['data'] = array();
            $result['error_code'] = RECEIPT_NOT_FOUND;
            $result['message'] = 'hóa đơn không tồn tại';
            // step 2

//            $cache_key = 'log_step2';
//            $time = 60 * 60;
//            Redis::set($cache_key, $receipt_info);
            return Response::json($result);
        }



        // get list apple item buy client id
        $itemlist = MerchantAppProductApple::get_products_apple_by_client_id($client_id);

        if (!is_array($itemlist)) {
            // item list not found, return error
            return $this->reponse_data(['errorCode' => LIST_ITEMS_APPLE_NOT_FOUND, 'errorMessage' => 'LIST_ITEMS_APPLE_NOT_FOUND', 'data' => array()]);
        }

        // find money to game
        $item = $this->search($itemlist, 'amount', $amount);
        if (!is_object($item)) {
            // item list not found, return error
            return $this->reponse_data(['errorCode' => LIST_ITEMS_APPLE_NOT_FOUND, 'errorMessage' => 'LIST_ITEMS_APPLE_NOT_FOUND', 'data' => array()]);
        }
        $price = $item->money_in_game;

        $resourceOwnerType = Authorizer::getResourceOwnerType();
        $resourceOwnerId = Authorizer::getResourceOwnerId();

        if ($resourceOwnerType === 'user') {
            $user = Auth::onceUsingId($resourceOwnerId);
        }

        $user = Auth::user();
        $uid = $user->id;
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        // create service
        $service = GameServices::createService($client_id);
        $trans_id = $this->createTransactionId($client_id, $uid);

        // transfer to game
        if ($response = $service->transfer($uid, $server_id, $trans_id, $price, $ip)) {
            $cpid = UtilHelper::getDefaultCpid(1, $client_id); // fix 1 - ios

            $arr = [
                'cpid' => $cpid,
                'uid' => $uid,
                'telco' => "1",
                'clientid' => $client_id,
                'serial' => "1",
                'amount' => $price * 100,
                'code' => "1",
            ];
            $revenuelog = new RevenueLogHelper;
            $revenuelog->setRevenue($arr);
            $data = array('price' => $price);
            $error_code = 200;
            $message = 'Mua vật phẩm thành công';
        } else {
            // show error
            $data = array('price' => $price);
            $error_code = FAIL_TRANSFER_COINS_TO_GAME;
            $message = 'Nạp tiền vào game thất bại, bạn vui lòng thử lại sau.';
        }
        // log transaction to db
        $arr_log['uid'] = $uid;
        $arr_log['server_id'] = $server_id;
        $arr_log['client_id'] = $client_id;
        $arr_log['trans_info'] = $receipt_info->getResultCode();
        $arr_log['ip'] = $ip;
        $arr_log['trans_id'] = $trans_id;
        $arr_log['status'] = $response ? 1 : 0;
        $arr_log['money_in_game'] = $price;
        $arr_log['response_time'] = date('Y-m-d H:i:s');
        $arr_log['response'] = $response;
        $arr_log['type'] = 'apple';
        $arr_log['receipt'] = json_encode($receipt_info->getReceipt());
        $this->logCoinTransfer($arr_log);

        // response
        $result = array();
        $result['data'] = $data;
        $result['error_code'] = $error_code;
        $result['message'] = $message;

        return Response::json($result);
    }

    public function missingMethod($parameters = array()) {

        return view('errors.404');
    }

    public function get_index() {
        dd('index');
    }

    private function reponse_data($arr) {
        $response['errorCode'] = $arr['errorCode'];
        //print_r($status);die;
        $response['errorMessage'] = $arr['errorMessage'];
        $response['data'] = $arr['data'];
        return Response::json($response);
    }

    private function search_array($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                if (array_search($value, $subarray)) {
                    return $subarray;
                    break;
                }
            }
        }

        return $results;
    }

    function search($array, $key, $value) {
        $item = null;
        foreach ($array as $struct) {
            if ($value == $struct->$key) {
                $item = $struct;
                break;
            }
        }
        return $item;
    }

    private function createTransactionId($client_id, $uid) {
        return $client_id . $uid . time() . rand(100000, 99999999);
    }

    private function validReceipt($receipt) {
        $validator = new iTunesValidator(iTunesValidator::ENDPOINT_PRODUCTION);

        //$receiptBase64Data = $this->getPurchaseInfo($receipt);
        //$receiptBase64Data = 'ewoJIm9yaWdpbmFsLXB1cmNoYXNlLWRhdGUtcHN0IiA9ICIyMDE1LTEyLTAyIDA2OjAzOjA2IEFtZXJpY2EvTG9zX0FuZ2VsZXMiOwoJInVuaXF1ZS1pZGVudGlmaWVyIiA9ICJmYjQ2ZWY5NDQ2MjYyODQ4YWZhYjExODJiYTk5YWYwZTM5MWY0MWIwIjsKCSJvcmlnaW5hbC10cmFuc2FjdGlvbi1pZCIgPSAiMTAwMDAwMDE4MzAxMTAwMSI7CgkiYnZycyIgPSAiMi4wLjIiOwoJInRyYW5zYWN0aW9uLWlkIiA9ICIxMDAwMDAwMTgzMDExMDAxIjsKCSJxdWFudGl0eSIgPSAiMSI7Cgkib3JpZ2luYWwtcHVyY2hhc2UtZGF0ZS1tcyIgPSAiMTQ0OTA2NDk4Njg1MiI7CgkidW5pcXVlLXZlbmRvci1pZGVudGlmaWVyIiA9ICIzQzEyOTJEMi1DMDJDLTQ0MTEtQjJGOC05QjFFRjZFOTA4MjYiOwoJInByb2R1Y3QtaWQiID0gImNvbS52aW5oeHVhbi52eDAyLnR1aXh1MSI7CgkiaXRlbS1pZCIgPSAiMTA1MzE2MDYwOSI7CgkiYmlkIiA9ICJjb20udmluaHh1YW4udngwMiI7CgkicHVyY2hhc2UtZGF0ZS1tcyIgPSAiMTQ0OTA2NDk4Njg1MiI7CgkicHVyY2hhc2UtZGF0ZSIgPSAiMjAxNS0xMi0wMiAxNDowMzowNiBFdGMvR01UIjsKCSJwdXJjaGFzZS1kYXRlLXBzdCIgPSAiMjAxNS0xMi0wMiAwNjowMzowNiBBbWVyaWNhL0xvc19BbmdlbGVzIjsKCSJvcmlnaW5hbC1wdXJjaGFzZS1kYXRlIiA9ICIyMDE1LTEyLTAyIDE0OjAzOjA2IEV0Yy9HTVQiOwp9';
         $receiptBase64Data = 'ewoJInNpZ25hdHVyZSIgPSAiQXBNVUJDODZBbHpOaWtWNVl0clpBTWlKUWJLOEVkZVhrNjNrV0JBWHpsQzhkWEd1anE0N1puSVlLb0ZFMW9OL0ZTOGNYbEZmcDlZWHQ5aU1CZEwyNTBsUlJtaU5HYnloaXRyeVlWQVFvcmkzMlc5YVIwVDhML2FZVkJkZlcrT3kvUXlQWkVtb05LeGhudDJXTlNVRG9VaFo4Wis0cFA3MHBlNWtVUWxiZElWaEFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09IjsKCSJwdXJjaGFzZS1pbmZvIiA9ICJld29KSW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRjSE4wSWlBOUlDSXlNREV5TFRBMExUTXdJREE0T2pBMU9qVTFJRUZ0WlhKcFkyRXZURzl6WDBGdVoyVnNaWE1pT3dvSkltOXlhV2RwYm1Gc0xYUnlZVzV6WVdOMGFXOXVMV2xrSWlBOUlDSXhNREF3TURBd01EUTJNVGM0T0RFM0lqc0tDU0ppZG5KeklpQTlJQ0l5TURFeU1EUXlOeUk3Q2draWRISmhibk5oWTNScGIyNHRhV1FpSUQwZ0lqRXdNREF3TURBd05EWXhOemc0TVRjaU93b0pJbkYxWVc1MGFYUjVJaUE5SUNJeElqc0tDU0p2Y21sbmFXNWhiQzF3ZFhKamFHRnpaUzFrWVhSbExXMXpJaUE5SUNJeE16TTFOems0TXpVMU9EWTRJanNLQ1NKd2NtOWtkV04wTFdsa0lpQTlJQ0pqYjIwdWJXbHVaRzF2WW1Gd2NDNWtiM2R1Ykc5aFpDSTdDZ2tpYVhSbGJTMXBaQ0lnUFNBaU5USXhNVEk1T0RFeUlqc0tDU0ppYVdRaUlEMGdJbU52YlM1dGFXNWtiVzlpWVhCd0xrMXBibVJOYjJJaU93b0pJbkIxY21Ob1lYTmxMV1JoZEdVdGJYTWlJRDBnSWpFek16VTNPVGd6TlRVNE5qZ2lPd29KSW5CMWNtTm9ZWE5sTFdSaGRHVWlJRDBnSWpJd01USXRNRFF0TXpBZ01UVTZNRFU2TlRVZ1JYUmpMMGROVkNJN0Nna2ljSFZ5WTJoaGMyVXRaR0YwWlMxd2MzUWlJRDBnSWpJd01USXRNRFF0TXpBZ01EZzZNRFU2TlRVZ1FXMWxjbWxqWVM5TWIzTmZRVzVuWld4bGN5STdDZ2tpYjNKcFoybHVZV3d0Y0hWeVkyaGhjMlV0WkdGMFpTSWdQU0FpTWpBeE1pMHdOQzB6TUNBeE5Ub3dOVG8xTlNCRmRHTXZSMDFVSWpzS2ZRPT0iOwoJImVudmlyb25tZW50IiA9ICJTYW5kYm94IjsKCSJwb2QiID0gIjEwMCI7Cgkic2lnbmluZy1zdGF0dXMiID0gIjAiOwp9';

        try {
            $response = $validator->setReceiptData($receiptBase64Data)->validate();
        } catch (Exception $e) {
            echo 'got error = ' . $e->getMessage() . PHP_EOL;
        }
        return $response;
        /*
          if ($response->isValid()) {
          echo 'Receipt is valid.' . PHP_EOL;
          echo 'Receipt data = ' . print_r($response->getReceipt()) . PHP_EOL;
          } else {
          echo 'Receipt is not valid.' . PHP_EOL;
          echo 'Receipt result code = ' . $response->getResultCode() . PHP_EOL;
          } */
    }

    private function logCoinTransfer($arr) {
        $log = new LogCoinTransfer;
        $log->request_time = $arr['request_time'];
        $log->user_id = $arr['uid'];
        $log->server_id = $arr['server_id'];
        $log->app_id = $arr['client_id'];
        $log->trans_info = $arr['trans_info'];
        $log->ip = $arr['ip'];
        $log->trans_id = $arr['trans_id'];
        $log->status = $arr['status'];
        $log->coin = $arr['money_in_game'];
        $log->response_time = $arr['response_time'];
        $log->response = json_encode($arr['response']);
        $log->type = $arr['type'];
        $log->receipt = json_encode($arr['receipt']);
        $log->save();
    }
    
    function getPurchaseInfo($receipt){
        $str = str_replace(array("\r", "\n", "\t", "\v","\"","{","}"), "", $receipt);
        $arr = explode(";", $str);
        foreach ($arr as $key =>$value){
            $arr_tmp = explode("=", $value);
            if(trim($arr_tmp[0]) == 'purchase-info'){
                return(trim($arr_tmp[1]));
            }
        }
        return FALSE;
    }

}
