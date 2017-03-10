<?php

namespace App\Http\Controllers\Auth;

use App\User, Response;
use Validator, App\Helpers\CustomOAuth2Resource as Checker;
use App\Http\Controllers\Controller;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Cache, App\Models\UserGame;
use App\Helpers\CommonHelper, cURL, Log;
class OAuthController extends Controller {

    protected $authorizer;
    protected $checker;

    public function __construct(Authorizer $authorizer, Checker $checker) {
        $this->authorizer = $authorizer;
        $this->checker = $checker;

//        $this->middleware('auth', ['only' => ['getAuthorize', 'postAuthorize']]);
        $this->middleware('csrf', ['only' => 'postAuthorize']);
        $this->middleware('check-authorization-params', ['only' => ['getAuthorize', 'postAuthorize']]);
    }

    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255|min:4|unique:users',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|confirmed|min:6',
                    'CaptchaCode' => 'required|valid_captcha',
        ]);
    }

    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                    'active' => 1,
        ]);
    }

    public function postAccessToken() {
        return Response::json($this->authorizer->issueAccessToken());
    }

    public function getAuthorize() {
        return View::make('authorization-form', $this->authorizer->getAuthCodeRequestParams());
    }

    public function postAuthorize(Request $request) {
        $params = Authorizer::getAuthCodeRequestParams();

        // validate request
        $validator = Validator::make($request->all(), [
                    'email' => 'required|max:255|min:4',
                    'password' => 'required|min:6'
        ]);

        // check token
        $token_form = Input::get('_token');
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
        $field = filter_var(Input::get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $array_login = [$field => Input::get('email'), 'password' => Input::get('password'), 'active' => 1];

        //login
        if (Auth::attempt($array_login, $request->has('remember_me'))) {
            $authParams['user_id'] = Auth::user()->id;

            if ($request->has('client_id')) {
                $apps = Cache::get('active_app_list');
                if (isset($apps[$request->input('client_id')])) {
                    $user_game = UserGame::firstOrNew(['uid' => $authParams['user_id'], 'app_id' => $apps[$request->input('client_id')]]);
                    $user_game->updated_at = date('Y-m-d H:i:s');
                    $user_game->save();
                }
            }

            $redirectUri = Authorizer::issueAuthCode('user', Auth::user()->id, $params);
            return redirect($redirectUri);
        }

        return redirect()->back()->withInput()->withErrors([
                    'error' => 'Sai thông tin đăng nhập hoặc bị khóa.',
        ]);
    }

    public function postRegister(Request $request) {
//        if (!$request->has('g-recaptcha-response')) {
//            return redirect()->back()->withErrors(['error[]' => 'Vui lòng xác thực bạn là con người, không phải máy'])->withInput();
//        }
//
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
//
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
        if (Auth::check()) {
            $params = Authorizer::getAuthCodeRequestParams();
            $authParams['user_id'] = Auth::user()->id;
            $redirectUri = Authorizer::issueAuthCode('user', Auth::user()->id, $params);
            return redirect($redirectUri);
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        return redirect('/');
    }

    public function validateAccessToken(Request $request) {
        if ($request->has('access_token')) {

            $valid = $this->checker->validateAccessToken(trim($request->input('access_token')));

            if ($valid) {
                $error_code = 200;
                $message = 'The "access_token" value is valid.';
            } else {
                $error_code = 402;
                $message = 'The "access_token" value is invalid.';
            }
        } else {
            $error_code = 401;
            $message = 'The "access_token" field is required.';
        }

        return Response::json([
            'data' => [],
            'error_code' => $error_code,
            'message' => $message
        ]);
    }
}
