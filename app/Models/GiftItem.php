<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftItem extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gift_item';
    public $timestamps = false;
    protected $fillable = ['facebookid', 'item','gift_code','created_at'];
}

