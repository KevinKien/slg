<?php

namespace App\Helpers;

use App\Models\User;

class OpenUser
{
    /**
     * @param $partner
     * @param $data : id, username, email, avatar, fullname
     * @return static
     * @internal param $partner_id
     */
    public static function firstOrCreate($partner, $data)
    {
        $pre_email = $data['id'];

        if (isset($data['username']) && !empty($data['username'])) {
            $pre_email = $data['username'];
        }

        switch ($partner) {
            case 'soha':
                $prefix = 'sh';
                $email = $pre_email . '@soha.vn';
                break;
            case 'zing':
                $prefix = 'zg';
                $email = $pre_email . '@zing.vn';
                break;
            case 'facebook':
                $prefix = 'fb';
                $email = $pre_email . '@facebook.com';
                break;
            case 'google':
                $prefix = 'gg';
                $email = $pre_email . '@google.com';
                break;
            default:
                $prefix = '';
                $email = $pre_email . '@slg.vn';
                break;
        }

        $name = $prefix . $data['id'];

        $user = User::where('name', $name)->first();

        if (!$user) {
            $password = str_random(8);

            if (isset($data['email']) && !empty($data['email'])) {
                $email = $data['email'];
            }

            $info = [
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'provider' => $partner,
                'provider_id' => $data['id'],
                'active' => 1,
            ];

            if (isset($data['avatar'])) {
                $info['avatar'] = $data['avatar'];
            }

            if (isset($data['fullname'])) {
                $info['fullname'] = $data['fullname'];
            }

            if (isset($data['sex'])) {
                $info['sex'] = $data['sex'];
            }

            if (isset($data['birthday'])) {
                $info['birthday'] = $data['birthday'];
            }

            $user = User::create($info);

//            $user->is_new = true;

            if (isset($data['email']) && !empty($data['email'])) {
                MailHelper::sendMailWelcome($user, $password);
            }
        }

        return $user;
    }
}