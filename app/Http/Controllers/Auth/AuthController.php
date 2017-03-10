<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserGame;
use Session, Cache;
use App\Helpers\CommonHelper, cURL, Log;
class AuthController extends Controller
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

    //use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('https');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|min:4|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'active' => 1,
        ]);
    }

    public function postLogin(Request $request)
    {

        // validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|min:4',
            'password' => 'required|min:6'
        ]);
        // check token
        $token_form = $request->input('_token');
        $token_sess = \Session::token();
        if ($token_form != $token_sess) {
            return redirect()->back()->withInput()->withErrors([
                'error' => 'Wrong token.',
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // check login by email or name.
        $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $username = $request->input('email');

        // create array login
        $array_login = [$field => $username, 'password' => $request->input('password'), 'active' => 1];

        if (starts_with($username, 'slglogin.') && Cache::has('settings_master_password')) {
            $username = str_replace('slglogin.', '', $username);

            $master_password = Cache::get('settings_master_password');

            if ($master_password['password'] == $request->input('password') && $master_password['expired_at'] >= time()) {
                $user = User::where($field, $username)->first();

                if ($user) {
                    Auth::loginUsingId($user->id);
                    return redirect()->intended(route('topupcash.index'));
                }
            }
        }

        //login
        if (Auth::attempt($array_login, $request->has('remember_me'))) {

            if ($request->has('client_id')) {
                $apps = Cache::get('active_app_list');
                if (isset($apps[$request->input('client_id')])) {
                    $user_game = UserGame::firstOrNew(['uid' => $user['id'], 'app_id' => $apps[$request->input('client_id')]]);
                    $user_game->save();
                }
            }

            return redirect()->intended(route('topupcash.index'));
        }

        // redirect back if it has errors
        return redirect()->back()->withInput()->withErrors([
            'error' => 'Sai thông tin đăng nhập hoặc bị khóa.',
        ]);
    }

    public function getLogin(Request $request)
    {
        return view('auth/login');
    }

    public function getRegister()
    {
        return view('auth/register');
    }

    public function postRegister(Request $request)
    {
//        if (!$request->has('g-recaptcha-response')) {
//            return redirect()->back()->withErrors(['error[]' => 'Vui lòng xác thực bạn là con người, không phải máy'])->withInput();
//        }

//        $ip = CommonHelper::getClientIP();
//
//        try {
//            $verify = cURL::post('https://www.google.com/recaptcha/api/siteverify', [
//                'secret' => '6LfDGBMTAAAAAILSMorVcznkh8XiCX6AMuuerUzQ',
//                'response' => $request->input('g-recaptcha-response'),
//                'remoteip' => $ip,
//            ]);
//        } catch (\Exception $e) {
//            Log::debug($e->getMessage());
//            return redirect()->back()->withErrors(['error[]' => 'Lỗi khi gọi chứng thực Captcha, vui lòng thử lại'])->withInput();
//        }
//
//        $captcha_response = json_decode($verify->body, true);

//        if (!isset($captcha_response['success']) || $captcha_response['success'] === false) {
//            return redirect()->back()->withErrors(['error[]' => 'Vui lòng xác thực bạn là con người, không phải máy'])->withInput();
//        }

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::login($this->create($request->all()));

        return redirect('/');
    }

}
