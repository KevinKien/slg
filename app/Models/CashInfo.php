<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CashInfo extends Model {

    protected $table = 'cash_info';
    protected $primaryKey = 'uid';
    public $incrementing = false;
    protected $fillable = ['uid', 'coins', 'point'];
    public $timestamps = false;

    public static function findOrCreate($uid) {
        $obj = static::where('uid', $uid)->firstOrFail();
        return $obj ? : new static;
    }

    public static function getCoins($uid = 0) {
        $coins = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $obj = static::where('uid', $user->id)->first();
            if (isset($obj->coins) && $obj->coins > 0) {
                $coins = $obj->coins;
            }
        }
        return $coins;
    }

    public static function getPoint($uid = 0) {
        $point = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $obj = static::where('uid', $user->id)->first();
            if (isset($obj->point) && $obj->point > 0) {
                $point = $obj->point;
            }
        }
        return $point;
    }

    public static function getCashInfo($uid) {
        $result = [
            'coin' => 0,
            'point' => 0,
        ];

        $cash = self::where('uid', $uid)->first();

        if ($cash) {
            if ($cash->coins > 0) {
                $result['coin'] = $cash->coins;
            }

            if ($cash->point > 0) {
                $result['point'] = $cash->point;
            }
        }

        return $result;
    }

    public static function incrementCoin($uid, $coin = 0) {
        if (empty($uid)) {
            return;
        }
        $cashinfo = CashInfo::findOrNew($uid);
        $cashinfo->uid = $uid;
        if (empty($cashinfo->coins)) {
            $cashinfo->coins = $coin;
        } else {
            $cashinfo->increment('coins', $coin);
        }

        if (empty($cashinfo->point)) {
            $cashinfo->point = $coin;
        } else {
            $cashinfo->increment('point', $coin);
        }

        return $cashinfo->save();
    }

    public static function decrementCoin($uid, $price = 0) {
        if (empty($uid)) {
            return;
        }
        $cashinfo = static::findOrNew($uid);
        $cashinfo->decrement('coins', $price);
        //$cashinfo->decrement('point', $price);
        return $cashinfo->save();
    }

}
