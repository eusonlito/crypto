<?php

return [
    'binance' => [
        'endpoint' => env('PLATFORM_BINANCE_ENDPOINT'),
        'socket' => env('PLATFORM_BINANCE_SOCKET'),
        'currency_innovation_allowed' => ['SHIB'],
    ],

    'coinbase-pro' => [
        'endpoint' => env('PLATFORM_COINBASE_PRO_ENDPOINT'),
        'socket' => env('PLATFORM_COINBASE_PRO_SOCKET'),
        'currency_innovation_allowed' => ['SHIB'],
    ],
];
