<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Newsletter Provider
    |--------------------------------------------------------------------------
    |
    | Choose which newsletter provider to use. Available options:
    | - 'database' - Store subscriptions in local database
    | - 'mailchimp' - Use Mailchimp API
    |
    */
    'provider' => env('NEWSLETTER_PROVIDER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Modal Settings
    |--------------------------------------------------------------------------
    |
    | Configure the newsletter modal popup behavior
    |
    */
    'modal' => [
        'enabled' => env('NEWSLETTER_MODAL_ENABLED', true),
        'delay_seconds' => env('NEWSLETTER_MODAL_DELAY', 10),
        'scroll_percentage' => env('NEWSLETTER_MODAL_SCROLL_PERCENT', 50),
        'hide_for_days' => env('NEWSLETTER_MODAL_HIDE_DAYS', 7),
    ],
];