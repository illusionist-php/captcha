<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default captcha driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default captcha driver that should be used
    | by the framework. The "image" captcha, as well as a variety of cloud
    | based captcha are available to your application.
    |
    */

    'default' => env('CAPTCHA_DRIVER', 'image'),

    /*
    |--------------------------------------------------------------------------
    | Captcha drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many captcha "drivers" as you wish, and you
    | may even configure multiple drivers of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "tencent", "geetest"
    |
    */

    'drivers' => [

        'tencent' => [
            'driver' => 'tencent',
            'id' => '',
            'key' => '',
        ],

        'geetest' => [
            'driver' => 'geetest',
            'id' => '',
            'key' => '',
        ],
    ],
];
