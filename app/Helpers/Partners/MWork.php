<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 6/6/2016
 * Time: 10:07 AM
 */

namespace App\Helpers\Partners;

use cURL, Log;

class MWork
{
    public static $app_id = '1004347158191940780200';
    public static $base_url = 'https://api.app360.vn/';
    public static $app_key = 'OwsxmBcQafAl5iHnJ7Xpht2HpAga4Xsw';
    public static $app_secret = 'CIA7THGO5YfhoiGin5BP61yjvMAYnY7wrSwvciU8UOX8Crn8';

    public static function register($uid, $cpid, $sub_cpid)
    {
        $payload = [
            'scoped_id' => (string) $uid,
            '_metadata' => [
                'channel' => (string) $cpid,
                'sub_channel' => (string) $sub_cpid,
            ],
        ];

        $t = 0;

        get:
        if ($t < 3) {
            try {
                $request = cURL::newJsonRequest('post', static::$base_url . 'scoped_id/v1/sessions/', $payload)
                    ->setUser(static::$app_id)->setPass(static::$app_secret);
//                ->setHeader('Authorization', 'Basic '. base64_encode(static::$app_id . ':' . static::$app_secret));
//                ->setOption(CURLOPT_USERPWD, static::$app_id . ':' . static::$app_secret);

                $_response = $request->send();

                $response = json_decode($_response->body, true);

                if (isset($response['scoped_id'])) {
                    $response['_provider'] = 'mwork';
                    return $response;
                }
            } catch (\Exception $e) {
                $t++;
                goto get;
            }
        }

        return false;
    }
}