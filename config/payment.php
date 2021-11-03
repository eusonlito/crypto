<?php

return [
    'stripe' => [
        'currency' => env('STRIPE_CURRENCY'),
        'locale' => env('STRIPE_LOCALE'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'items' => [
            'base' => [
                'price' => env('STRIPE_SUBSCRIPTION_BASE_PRICE'),
                'metadata' => ['code' => 'base'],
            ],

            'folder' => [
                'price' => env('STRIPE_SUBSCRIPTION_FOLDER_PRICE'),
                'metadata' => ['code' => 'folder'],
            ],

            'user' => [
                'price' => env('STRIPE_SUBSCRIPTION_USER_PRICE'),
                'metadata' => ['code' => 'user'],
            ],
        ],
    ],
];
