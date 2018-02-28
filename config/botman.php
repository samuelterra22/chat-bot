<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facebook Messenger.
    |--------------------------------------------------------------------------
    |
    */

    'facebook' => [
        'token'        => env('BM_FACEBOOK_PAGE_TOKEN', null),
        'app_secret'   => env('BM_FACEBOOK_APP_SECRET', null),
        'verification' => env('BM_FACEBOOK_VERIFICATION', null),
    ]
];