<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    protected $table = 'devices';
    public $timestamps = true;
    protected $fillable = ['device_id', 'os_id', 'client_id'];
}
