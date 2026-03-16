<?php

return [
    'client_id' => env('HALKPAY_CLIENT_ID', ''),
    'store_key' => env('HALKPAY_STORE_KEY', ''),
    'store_type' => env('HALKPAY_STORE_TYPE', '3D_PAY_HOSTING'),
    'currency' => env('HALKPAY_CURRENCY', '807'),
    'transaction_type' => env('HALKPAY_TRANSACTION_TYPE', 'Auth'),
    'success_url' => env('HALKPAY_SUCCESS_URL', config('app.url') . '/payments/success'),
    'fail_url' => env('HALKPAY_FAIL_URL', config('app.url') . '/payments/fail'),
    'lang' => env('HALKPAY_LANG', 'mk'),
    'gateway_url' => env('HALKPAY_GATEWAY_URL', 'https://torus-stage-halkbankmacedonia.asseco-see.com.tr/fim/est3Dgate'),

    'routes' => [
        'enabled' => env('HALKPAY_ROUTES_ENABLED', true),
        'prefix' => env('HALKPAY_ROUTE_PREFIX', 'halkpay'),
        'middleware' => ['web'],
        'name_prefix' => 'halkpay.',
    ],

    'callback' => [
        'response_mode' => env('HALKPAY_CALLBACK_RESPONSE_MODE', 'json'), // json|redirect|view
        'success_redirect_to' => env('HALKPAY_CALLBACK_SUCCESS_REDIRECT_TO', '/'),
        'fail_redirect_to' => env('HALKPAY_CALLBACK_FAIL_REDIRECT_TO', '/'),
        'success_view' => env('HALKPAY_CALLBACK_SUCCESS_VIEW', 'halkpay::payments.success'),
        'fail_view' => env('HALKPAY_CALLBACK_FAIL_VIEW', 'halkpay::payments.fail'),
    ],
];
