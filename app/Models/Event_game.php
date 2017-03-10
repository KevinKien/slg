<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Event_game extends Model
{
    protected $table = 'events';
    public $timestamps = true;
    protected $guarded = [];

    public static function EventCodeTypePublic ($event_id,$code_type,$status){
        $event = DB::table('events')->where('id',$event_id)->where('giftcode_type',$code_type)->where('status',$status)->first();
        return $event;
    }
}

