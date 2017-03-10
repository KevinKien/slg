<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Socialite, Exception;
use Illuminate\Support\Facades\Auth;
use App\Helpers\MailHelper;

class AuthOpenIdController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

    use AuthenticatesAndRegistersUsers;

    //public function __construct(Socialite $socialite)
    //{
    //$this->socialite = $socialite;
    //}

    public function getSocialAuth($provider = null)
    {
        if (!config("services.$provider"))
            abort('404'); //just to handle providers that doesn't exist

        return Socialite::driver($provider)->redirect();
    }

    public function getSocialAuthCallback($provider = null)
    {
        try {
            $op_user = Socialite::driver($provider)->user();

            if ($op_user) {

                $opid = $op_user->id;
                $name = $op_user->name;
                $email = $op_user->email;

                switch ($provider) {
                    case 'facebook':
                        $name = 'fb' . $opid;
                        $email = isset($op_user->email) ? $op_user->email : ($opid . '@facebook.com');
                        break;
                    case 'google':
                        $name = 'gg' . $opid;
                        break;
                    default :
                        break;
                }

                if (!isset($op_user->email) || empty($op_user->email)) {
                    $user = User::findOpenUserByName($name);
                } else {
                    $user = User::findOpenUserByEmail($op_user->email);
                }

                $password = str_random(8);

                if (!$user) {

                    $newuser = array(
                        'name' => $name,
                        'email' => $email,
                        'avatar' => isset($op_user->avatar) ? $op_user->avatar : null,
                        'password' => bcrypt($password),
                        'provider' => $provider,
                        'provider_id' => $op_user->id,
                        'fullname' => $op_user->name,
                        'active' => 1,
                    );

                    $user = User::firstOrCreate($newuser);

                    if (isset($op_user->email) && !empty($op_user->email)) {
                        MailHelper::sendMailWelcome($user, $password);
                    }
                }

                if ($user->active == 0)
                {
                    return redirect()->intended('auth/login');
                }

                if (Auth::loginUsingId($user->id)) {
                    return redirect()->intended('/');
                }
            }

            return redirect()->intended('auth/login');
        } catch (\Exception $e) {
            return redirect()->intended('auth/login');
        }
    }
}