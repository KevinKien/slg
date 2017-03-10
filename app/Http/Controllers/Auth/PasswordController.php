<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Helpers\CommonHelper, cURL, Log;

class PasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

    //use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('https');
    }

    public function getEmail() {
        if (Auth::check()) {
            Auth::logout();
        }
        return view('auth.password');
    }

    public function postEmail(Request $request) {
        if (!$request->has('g-recaptcha-response')) {
            return redirect()->back()->withErrors(['error[]' => 'Vui lòng xác thực bạn là con người, không phải máy'])->withInput();
        }

        $ip = CommonHelper::getClientIP();

        try {
            $verify = cURL::post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => '6LfDGBMTAAAAAILSMorVcznkh8XiCX6AMuuerUzQ',
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $ip,
            ]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors(['error[]' => 'Lỗi khi gọi chứng thực Captcha, vui lòng thử lại'])->withInput();
        }

        $captcha_response = json_decode($verify->body, true);

        if (!isset($captcha_response['success']) || $captcha_response['success'] === false) {
            return redirect()->back()->withErrors(['error[]' => 'Vui lòng xác thực bạn là con người, không phải máy'])->withInput();
        }

        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject($this->getEmailSubject());
                });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));

            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }
    }

    protected function getEmailSubject() {
        return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
    }

    public function getReset($token = null) {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('auth.reset')->with('token', $token);
    }

    public function postReset(Request $request) {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $credentials = $request->only(
                'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
                    $this->resetPassword($user, $password);
                });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath());

            default:
                return redirect()->back()
                                ->withInput($request->only('email'))
                                ->withErrors(['email' => trans($response)]);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password) {
        $user->password = bcrypt($password);

        $user->save();

        Auth::login($user);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath() {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    protected static function getFacadeAccessor() {
        return 'auth.password';
    }

}
