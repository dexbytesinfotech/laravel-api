<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default values for commerce data
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */


    'faker_lang' => env('FAKER_LANG', 'en_US'),
    'currency_symbol' => env('CURRENCY_SYMBOL', '$'),
    'currency' => env('CURRENCY', 'USD'),
    'app_logo' => env('APP_LOGO', ''),
    'app_favicon_logo' => env('APP_FAVICON_LOGO', ''),


    'pagination_per_page' => env('PAGINATION_PER_PAGE', 50),
    'message_pagination_per_page' => env('MESSAGE_PAGINATION_PER_PAGE', 50),

    'currency' => env('CURRENCY', 'SR'),
    'longitude_default'  => env('LONGITUDE_DEFAULT', 75.891418),
    'latitude_default'  => env('LATITUDE_DEFAULT', 22.758940),

    'store_default_logo_image' => env('STORE_DEFAULT_LOGO_IMAGE', 'https://source.unsplash.com/user/c_v_r/100x100'),
    'store_default_background_image' => env('STORE_DEFAULT_BACKGROUND_IMAGE', 'https://source.unsplash.com/user/c_v_r/100x300'),

    'tags_default'  => env('TAGS_PRODUCT', 15),

    'push_notification_cron_slack' => env('PUSH_NOTIFICATION_CRON_SLACK', false),
    'default_country_name' => env('DEFAULT_COUNTRY_NAME', 'United States'),
    'default_country_code' => env('DEFAULT_COUNTRY_CODE', 1),
    'default_country_iso_code' => env('DEFAULT_COUNTRY_ISO_CODE', 'US'),
    'default_nationality' => env('DEFAULT_NATIONALITY', 'United States'),
    'default_state' => env('DEFAULT_STATE', 'Los Angeles'),
    'default_city' => env('DEFAULT_CITY', 'Los Angeles'),

    'enable_sms_notifications' => env('ENABLE_SMS_NOTIFICATIONS', false),
];
