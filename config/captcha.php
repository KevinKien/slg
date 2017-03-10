<?php if (!class_exists('CaptchaConfiguration')) { return; }

// BotDetect PHP Captcha configuration options
// more details here: https://captcha.com/doc/php/captcha-options.html
// ----------------------------------------------------------------------------

return [
    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for example page
    |--------------------------------------------------------------------------
    */
    'ExampleCaptcha' => [
        'UserInputID' => 'CaptchaCode',
        'CodeLength' => 4,
        'ImageWidth' => 250,
        'ImageHeight' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for contact page
    |--------------------------------------------------------------------------
    */
    'ContactCaptcha' => [
        'UserInputID' => 'CaptchaCode',
        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(4, 6),
        'ImageStyle' => ImageStyle::AncientMosaic,
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for login page
    |--------------------------------------------------------------------------
    */
    'LoginCaptcha' => [
        'UserInputID' => 'CaptchaCode',
        'CodeLength' => 3,
        'ImageStyle' => [
            ImageStyle::Radar,
            ImageStyle::Collage,
            ImageStyle::Fingerprints,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for register page
    |--------------------------------------------------------------------------
    */
    'RegisterCaptcha' => [
        'UserInputID' => 'CaptchaCode',
        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(6,9),
        'CodeStyle' => CodeStyle::Alpha,
        'HelpLinkText' => 'SLG BotDetect',
        'ImageStyle' => [
            ImageStyle::BlackOverlap,
            ImageStyle::Overlap,
            ImageStyle::Overlap2,
            ImageStyle::Snow,
            ImageStyle::Stitch,
            ImageStyle::Fingerprints,
            ImageStyle::Graffiti,
            ImageStyle::Graffiti2,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration for reset password page
    |--------------------------------------------------------------------------
    */
    'ResetPasswordCaptcha' => [
        'UserInputID' => 'CaptchaCode',
        'CodeLength' => 2,
        'CustomLightColor' => '#9966FF',
    ],

    // Add more your Captcha configuration here...
];
