<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | NatCash credentials
    |--------------------------------------------------------------------------
    |
    | The merchant credentials issued by Natcom for your NatCash account.
    | Keep them out of version control by reading them from the environment.
    |
    */

    'private_key' => env('NATCASH_PRIVATE_KEY', ''),

    'partner_code' => env('NATCASH_PARTNER_CODE', ''),

    'function_code' => env('NATCASH_FUNCTION_CODE', ''),

    'username' => env('NATCASH_USERNAME', ''),

    'password' => env('NATCASH_PASSWORD', ''),

    'callback_url' => env('NATCASH_CALLBACK_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | enable_fee toggles whether the fee is charged to the payer. language is
    | the gateway UI language ('ht', 'fr', 'en'). debug switches to the
    | sandbox gateway; it defaults to false (live), set NATCASH_DEBUG=true
    | in local environments.
    |
    */

    'enable_fee' => (bool) env('NATCASH_ENABLE_FEE', true),

    'language' => env('NATCASH_LANGUAGE', 'ht'),

    'debug' => (bool) env('NATCASH_DEBUG', false),
];
