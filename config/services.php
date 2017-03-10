<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => '',
        'secret' => '',
    ],
    
    //Socialite
    'facebook' => [
        'client_id'     => '1495360114089296',
        'client_secret' => 'c0c919a4c5d7f12d179ef82476933e5d',
        'redirect'      => 'http://id.slg.vn/auth/login/callback/facebook',
    ],
    
    //Socialite
    'google' => [
        'client_id'     => '1062569195313-j08qkgnecn0e6p94rt67tk0qctcoiikr.apps.googleusercontent.com',
        'client_secret' => 'TZerKgRw0TGPtct92wCXFoIJ',
        'redirect'      => 'http://id.slg.vn/auth/login/callback/google',
    ]

];