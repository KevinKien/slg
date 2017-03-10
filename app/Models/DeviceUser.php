<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceUser extends Model
{
    protected $table = 'device_user';
    public $timestamps = true;
    protected $fillable = ['uid', 'device_id', 'os_id', 'client_id'];
}
