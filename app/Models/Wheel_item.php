<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Cache,
    Response,
    Redis, DB;

class Wheel_item extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wheel_item';

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

    public static function insertWheelitem($event, $item,$item_number,$image,$is_use,$dial,$item_quantity) {
        $time = time();
        $query = DB::table('wheel_item')
            ->insert(
                array('event' => $event,
                    'item' => $item,
                    'item_quantity' => $item_quantity,
                    'item_number' => $item_number,
                    'image_item' => $image,
                    'is_use' => $is_use,
                    'turn_dial' => $dial,
                    'created_at' => date('Y-m-d H:i:s',$time),
                ));

        return 200;
    }

    public static function  updateWheelitemuse(){
        $query = DB::table('wheel_item')->update(
            ['is_use' => 0]);
        return 200;
    }


}

