<?php

return [
    'seller' => [
        'name' => env('INVOICE_SELLER_NAME', config('app.name', 'Your Store')),
        'phone' => env('INVOICE_SELLER_PHONE', '+1234567890'),
        'email' => env('INVOICE_SELLER_EMAIL', 'info@yourstore.com'),
        'website' => env('INVOICE_SELLER_WEBSITE', 'www.yourstore.com'),
        'address' => env('INVOICE_SELLER_ADDRESS', '123 Main St, City, Country'),
    ],

    'defaults' => [
        'currency_symbol' => '€',
        'currency_code' => 'EUR',
        'pay_until_days' => 30,
        'series_prefix' => 'INV',
        'logo_path' => 'images/logo.png',
    ],

    'storage' => [
        'disk' => 'public',
        'path' => 'invoices/',
    ],
];