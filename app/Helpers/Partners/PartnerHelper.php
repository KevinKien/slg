<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 6/6/2016
 * Time: 3:52 PM
 */

namespace App\Helpers\Partners;

use Cache;

class PartnerHelper
{
    public static function register($uid, $cpid, $sub_cpid = 0)
    {
        if ($cpid == 300000198)
        {
            return MWork::register($uid, $cpid, $sub_cpid);
        } elseif ($cpid == 300000196)
        {
            return ['_provider' => 'gmob'];
        }

        return null;
    }
}