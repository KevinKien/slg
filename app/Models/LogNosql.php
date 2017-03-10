<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core_partner_info;
use App\Models\Merchant_app_cp;
use DB;

class LogNosql extends Model {
    protected $table = 'core_log_nosql';
    public $timestamps = true;
    protected $fillable = ['key','value'];
}