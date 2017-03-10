<?php
namespace App\Http\Controllers\ApiProduct;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Cache;
use App\Models\MerchantAppProduct;
use ArrayPaginator;
use CommonHelper;
use Response;
class ApiProductController extends Controller
{
    public function index()
    {
         $appid = $_GET['appid'];
         $result =MerchantAppProduct::get_products_by_appid($appid);
         if ($result == 31) {
            $status = 31;
                $result = null;
        } else
                $status = 200;
        return reponse_data($status, $result);
    }
}

 function reponse_data($status,$result)
	{		
		$response['errorCode'] =$status;
		//print_r($status);die;
		$response['errorMessage'] = CommonHelper::convertErrorCode($status);
		$response['data'] = $result;
		return Response::json($response);	
	}

?>