<?php

return [


   /*
    |--------------------------------------------------------------------------
    | Default SMS
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send any email
    | messages sent by your application. Alternative mailers may be setup
    | and used as needed; however, this mailer will be used by default.
    |
    */

    'default' => env('SMS_CHANNEL', ''),


    /*
    |--------------------------------------------------------------------------
    | TWILIO_SID="INSERT YOUR TWILIO SID HERE"
    | TWILIO_AUTH_TOKEN="INSERT YOUR TWILIO TOKEN HERE"
    | TWILIO_NUMBER="INSERT YOUR TWILIO NUMBER IN [E.164] FORMAT"
    |--------------------------------------------------------------------------
    |
    */
    'twillo' => [
                'twilio_sid' => env('TWILIO_SID', ''),
                'twilio_auth_token' => env('TWILIO_AUTH_TOKEN', ''),
                'twilio_number' => env('TWILIO_NUMBER', '')
    ],
    /*
    |--------------------------------------------------------------------------
    | SMSGATEWAYHUB_SID="INSERT YOUR unifonic SENDER ID HERE"
    | SMSGATEWAYHUB_AUTH_TOKEN="INSERT YOUR unifonic TOKEN HERE"
    |--------------------------------------------------------------------------
    |
    */
    'unifonic' => [
        'unifonic_url' => env('UNIFONIC_URL', ''),
        'unifonic_sid' => env('UNIFONIC_SID', ''),
        'unifonic_auth_token' => env('UNIFONIC_AUTH_TOKEN', ''),
    ]
];
