<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'app_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['app_id', 'user_ip'];
}