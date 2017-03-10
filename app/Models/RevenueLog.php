<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueLog extends Model
{
    protected $table = 'revenue_log';
    public $timestamps = true;
    protected $fillable = ['date', 'revenue', 'cpid'];
}
