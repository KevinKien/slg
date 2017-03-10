<?php

namespace App\Http\Middleware;

use Closure;

class ThienLongAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->server('PHP_AUTH_USER') != 'user' || $request->server('PHP_AUTH_PW') != 'password') {
            $appid = $_GET['appid'];

            $cpid = $_GET['cpid'];

            $sub_cpid = isset($_GET['sub_cpid']) && $_GET['sub_cpid'] ? $_GET['sub_cpid'] : 0;

            $requestid = $_GET['requestid'];

            $timereq = $_GET['timereq'];

            $command = $_GET['command'];

            $data = $_GET['data'];

            $sign = $_GET['sign'];

            if (!$request->has())
            $status = 2;

            return Response::json([
                'data' => '',
                'errorCode' => $status,
                'errorMessage' => convertErrorCode($status)
            ]);
        }

//        $response['errorCode'] =$status;
//        //print_r($status);die;
//        $response['errorMessage'] = convertErrorCode($status);
//        $response['data'] = $result;
//        $json_response = json_encode($response);
//        //echo "1";
//        header("Content-type: application/json; charset=utf-8");
//        print_r($json_response);

        return $next($request);
    }
}
