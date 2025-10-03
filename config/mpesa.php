<?php



return [
    'env' => env('MPESA_ENV', 'sandbox'),

    'base_url' => env('MPESA_ENV', 'sandbox') === 'sandbox'
        ? 'https://sandbox.safaricom.co.ke'
        : 'https://api.safaricom.co.ke',

    'shortcode' => env('MPESA_SHORTCODE'),

    'till_number' => env('MPESA_TILL_NUMBER'), // if you use Till instead of Paybill

    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),

    'validation_url' => env('MPESA_VALIDATION_URL'),
    'confirmation_url' => env('MPESA_CONFIRMATION_URL'),
];
