<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use Hash, App\Helpers\DrupalPassword;
use Mail;
use Cache;
use Carbon\Carbon;
use App\Http\Requests\ChangePassFormRequest;
use App\Http\Requests\ChangeInfoFormRequest;
use App\Http\Requests\VerifyFormRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\EmailValidatorController;
use Session;
use Auth;
use CommonHelper;

class ProfileController extends Controller
{
    public function __construct(Request $request)
    {
        $this->beforeFilter(function () use ($request) {
            $callback = $request->url();
            if (!Auth::check()) {
                return redirect('http://id.slg.vn/auth/login?callback=' . $callback);
            }
        });
    }

    public function missingMethod($parameters = array())
    {

//        return view('errors.404');
        return redirect('profile/personal');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function get_index()
    {
        $data['selectbar'] = 'profile';
        return view('profile.web.general', $data);
        //
    }

    public function getPersonal()
    {
        $data['selectbar'] = 'personal';
        return view('profile.web.personal', $data);
        //
    }

    /**
     * Display change password form
     *
     * @return Response
     */
    public function getChangepass()
    {

        return view('profile.web.changepass');

    }

    /**
     * Change password function
     *
     * @return Response
     */
    public function postChangepass(ChangePassFormRequest $request)
    {
        $credentials = $request->only(
            '_token', 'password', 'password_confirmation', 'password_old'
        );
        if ($credentials['_token'] != csrf_token()) {
            return redirect()->back()
                ->withErrors(['error[]' => 'Mã xác thực không chính xác']);
        }
        $user = \Auth::user();

        if (Hash::check($credentials['password_old'], $user->password) || DrupalPassword::validate($credentials['password_old'], $user->password)) {
            $user->password = bcrypt($credentials['password']);
            $user->save();

            Session::flash('success', 'Thay đổi password thành công !');
            return redirect('profile/changepass');
        } else {
            return redirect()->back()
                ->withErrors(['error[]' => 'Pass cũ không chính xác']);
        }
    }

    public function getChangepersonalinfo()
    {

        $user_info = Auth::user();
        return view('profile.web.changepersonalinfo');
    }

    public function postChangepersonalinfo(ChangeInfoFormRequest $request)
    {
        $credentials = $request->only(
            '_token', 'fullname', 'sex', 'birthday', 'identify', 'address', 'phone', 'email'
        );
//            print_r(date('Y-m-d 0:0:0', strtotime($credentials['birthday'])));die;
        if ($credentials['_token'] != csrf_token()) {
            return redirect()->back()
                ->withErrors(['error' => 'Mã xác thực không chính xác']);
        }

        if (!empty($credentials['email'])) {
            $truemail = CommonHelper::isValidEmail($credentials['email']);

            if (!$truemail) {
                return redirect()->back()
                    ->withErrors(['email' => 'Email nhập vào không tồn tại. Trường mail không thể sửa. Bạn hãy nhập chính xác email đang sử dụng!!']);
            }
        }
        $user = \Auth::user();
        $user->fullname = $credentials['fullname'];
        $user->sex = $credentials['sex'];
        if (!empty($credentials['birthday'])) {
            $user->birthday = date('Y-m-d 0:0:0', strtotime($credentials['birthday']));
        }
        if (!empty($credentials['identify'])) {
            $user->identify = $credentials['identify'];
        }
        if (!empty($credentials['address'])) {
            $user->address = $credentials['address'];
        }
        if (!empty($credentials['email'])) {
//            $user->email = $credentials['email'];
            $time_live = Carbon::now()->addMinutes(30);
            $key_verify = "verify_" . $user->id;
            $key_email = "verify_email_" . $user->id;
            $value_verify = md5($credentials['email'] . $user->id);
            Cache::put($key_verify, $value_verify, $time_live);
            Cache::put($key_email, $credentials['email'], $time_live);
            Mail::send('emails.confirm', ['email' => $credentials['email'], 'verify' => $value_verify], function ($message) use ($credentials) {
                $message->from('support@slg.vn', 'Slg');
                $message->to($credentials['email'], $credentials['fullname']);
                $message->replyTo('support@slg.vn', 'Slg');
                $message->subject('Xác nhận đăng ký email cho tài khoản ');
            });
        }
        if (!empty($credentials['phone'])) {
            $user->phone = $credentials['phone'];
        }
        $user->save();
        if (!empty($credentials['email'])) {
            return redirect('profile/verifyemail');
//            $data['verify_token'] = $token ;
        } else {
            Session::flash('success', 'Change info sucess!');
            return redirect('profile/personal');
        }
    }

    public function getVerifyemail()
    {
        $user = \Auth::user();
        $key_verify = "verify_" . $user->id;
        if (Cache::has($key_verify)) {
            return view('profile.web.verifyemail');
        } else {
            return redirect('profile/personal');
        }
    }

    public function postVerifyemail(VerifyFormRequest $request)
    {
        $credentials = $request->only(
            '_token', 'verify');
        $user = \Auth::user();
        $key_verify = "verify_" . $user->id;
        $key_email = "verify_email_" . $user->id;

        if (!Cache::has($key_verify)) {
            return redirect('profile/personal');
        } else {
            $verify_value = Cache::get($key_verify);
            $verify_email = Cache::get($key_email);
            if (strtolower($credentials['verify']) == $verify_value) {
                $user->email = $verify_email;
                $user->save();
                Session::flash('success', 'Change info sucess!');
                return redirect('profile/personal');
            } else {
                return redirect()->back()
                    ->withErrors(['verify' => "Mã xác thực sai"]);
            }
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
