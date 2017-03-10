<?php

namespace App\Grants;

use App\Models\User;

class UserGrantVerifier {

    public function verify($username) {
        $field = filter_var($username , FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::where($field, $username)->first();

        if ($user) {
            return $user->id;
        }

        return false;
    }

}
