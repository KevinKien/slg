<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift_code extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gift_code';
    public $timestamps = false;
    protected $fillable = ['giftcode', 'item','created_at','id_wheel','is_use'];
}

