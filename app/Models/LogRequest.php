<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    protected $table = 'log_request';
    public $timestamps = true;
    protected $fillable = ['url', 'payload', 'ip', 'response_code', 'response_time', 'response_content'];
}
