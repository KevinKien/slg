<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon,
    DB;

class LogCoinRestore extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_coin_restore';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
