<?php

namespace App\Grants;

use Auth;

class PasswordGrantVerifier {

    public function verify($username, $password) {
        $field = filter_var($username , FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $field => $username,
            'password' => $password,
            'active' => 1,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }

}
