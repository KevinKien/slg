<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGameServer extends Model
{
    protected $table = 'user_game_server';
    public $timestamps = true;
    protected $fillable = ['ugid', 'server_id'];
}
