<?php

namespace App\Http\Controllers\ApiProduct;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\MerchantAppProductApple;
use ArrayPaginator;
use CommonHelper;
use Response;
use Illuminate\Support\Facades\Input;

class ApiProductAppleController extends Controller {

    public function index() {
        $client_id = Input::get('client_id');
        $result = MerchantAppProductApple::get_products_apple_by_client_id($client_id);
        if ($result == 31) {
            $status = 31;
            $result = null;
        } else
            $status = 200;
        return $this->reponse_data($status, $result);
    }

    function reponse_data($status, $result) {
        $response['errorCode'] = $status;
        //print_r($status);die;
        $response['errorMessage'] = CommonHelper::convertErrorCode($status);
        $response['data'] = $result;
        return Response::json($response);
    }

}

?>