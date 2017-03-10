<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request, Route, Response, Validator;
use App\Models\MerchantApp, cURL, Cache;

class SocialAuthenticationController extends Controller
{
    protected function validateInput($request)
    {
        $rules = [
            'access_token' => 'required',
            'client_id' => 'required|exists:oauth_clients,id|exists:merchant_app,clientid',
            'client_secret' => 'required|exists:oauth_clients,secret',
        ];

        $messages = [
            'required' => '401|The ":attribute" field is required.',
            'exists' => '402|The ":attribute" field is invalid.',
        ];

        $input = array_map('trim', $request->all());

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $error = explode('|', $errors[0]);

            return [
                'data' => [],
                'error_code' => (int)$error[0],
                'message' => $error[1]
            ];
        }

        return $input;
    }

    public function google(Request $request)
    {
        $input = $this->validateInput($request);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $response = CURL::get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $input['access_token']);

        $result = json_decode($response->body, true);

        if (isset($result['error'])) {
            return Response::json([
                'data' => [],
                'error_code' => 403,
                'message' => 'The "access_token" field is invalid or An error occurred while retrieving the user information.'
            ]);
        } else {
            $name = isset($result['user_id']) ? 'gg' . $result['user_id'] : $result['email'];

            $password = str_random(8);

            $user = User::where('name', $name)->first();

            if ($user) {
                $user->password = bcrypt($password);
                $user->save();
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $result['email'],
                    'password' => bcrypt($password),
                    'provider' => 'google',
                    'provider_id' => isset($result['user_id']) ? $result['user_id'] : $result['email'],
                    'active' => 1,
                ]);
            }

            $response = cURL::post(route('user-login'), [
                'username' => $name,
                'password' => $password,
                'client_id' => $input['client_id'],
                'client_secret' => $input['client_secret'],
                'email' => $result['email'],
                'cpid' => $request->input('cpid'),
            ]);

            return Response::json(json_decode($response->body, true));
        }
    }

    public function facebook(Request $request)
    {
        $input = $this->validateInput($request);

        if (isset($input['error_code'])) {
            return Response::json($input);
        }

        $response = CURL::get('https://graph.facebook.com/me?access_token=' . $input['access_token']);
        $result = json_decode($response->body, true);

        if (!isset($result['id'])) {
            return Response::json([
                'data' => [],
                'error_code' => 403,
                'message' => 'The "access_token" field is invalid or An error occurred while retrieving the user information.'
            ]);
        }

        $name = 'fb' . $result['id'];

        $password = str_random(8);

        $user = User::where('name', $name)->first();

        $email = $result['id'] . '@facebook.com';

        if ($user) {
            $user->password = bcrypt($password);
            $user->save();
        } else {
            $user = User::create([
                'name' => $name,
                'fullname' => $result['name'],
                'email' => $email,
                'password' => bcrypt($password),
                'provider' => 'facebook',
                'provider_id' => $result['id'],
                'active' => 1,
            ]);
        }

        $response = cURL::post(route('user-login'), [
            'username' => $name,
            'password' => $password,
            'client_id' => $input['client_id'],
            'client_secret' => $input['client_secret'],
            'email' => $email,
            'cpid' => $request->input('cpid'),
        ]);

        return Response::json(json_decode($response->body, true));
    }
}
