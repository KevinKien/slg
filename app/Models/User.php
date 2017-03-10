<?php

namespace App\Models;

use Kodeine\Acl\Traits\HasRole;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable,
        CanResetPassword,
        HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'avatar', 'provider', 'provider_id', 'fullname', 'sub_cpid', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public static function findOpenUserByEmail($email, $colums = array('*'))
    {
        if (!is_null($user = static::whereEmail($email)
            //->whereProvider($provider)
            //->whereProvider_id($provider_id)
            ->first($colums)
        )
        ) {
            return $user;
        }
        return FALSE;
    }
    public static function findOpenUserByName($name, $colums = array('*'))
    {
        if (!is_null($user = static::whereName($name)
            //->whereProvider($provider)
            //->whereProvider_id($provider_id)
            ->first($colums)
        )
        ) {
            return $user;
        }
        return FALSE;
    }

    public static function findOpenUserById($id, $colums = array('*'))
    {
        if (!is_null($user = static::whereId($id)
            ->first($colums)
        )
        ) {
            return $user;
        }
        return FALSE;
    }

    public static function findUserByProviderId($pid, $columns = ['*'])
    {
        return static::where('provider_id', $pid)->first($columns);
    }
}
