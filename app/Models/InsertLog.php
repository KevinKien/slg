<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class InsertLog extends Model {

    protected $table = 'log_charge_coin_telco';

    //protected $table2 = 'log_charge_coin_telco';

    protected $fillable = ['uid','trans_id','card_type','coin','amount','partner_type','payment_status','created_at','updated_at'];
    public $timestamps = false;
   

}

?>
