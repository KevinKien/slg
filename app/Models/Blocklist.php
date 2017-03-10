<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;

class Blocklist extends Model {

    protected $table = 'blocked_list';

    //protected $table2 = 'log_charge_coin_telco';

    protected $fillable = ['uid', 'username', 'card_telco', 'visa_nganluong', 'visa_napas', 'atm_napas','coin_transfer', 'created_at'];
    public $timestamps = false;
   

}

?>
