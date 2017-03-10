<?php
namespace app\Helpers\Games;

use Cache;

class GameHelper
{
    public static function isInReview($client_id, $sdk_version)
    {
        $key = 'inreview_sdk_' . $client_id;

        if (Cache::has($key) && $sdk_version !== '')
        {
            $inreview_sdk = Cache::get($key);

            if (version_compare($sdk_version, $inreview_sdk['inreview_sdk_version'], $inreview_sdk['inreview_operator'])) {
                return true;
            }
        }

        return false;
    }
}