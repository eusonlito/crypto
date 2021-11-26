<?php

return [
    'binance' => [
        'endpoint' => env('PLATFORM_BINANCE_ENDPOINT'),
        'socket' => env('PLATFORM_BINANCE_SOCKET'),
    ],

    'coinbase-pro' => [
        'endpoint' => env('PLATFORM_COINBASE_PRO_ENDPOINT'),
        'socket' => env('PLATFORM_COINBASE_PRO_SOCKET'),
    ],

    'kucoin' => [
        'endpoint' => env('PLATFORM_KUCOIN_ENDPOINT'),
        'socket' => env('PLATFORM_KUCOIN_SOCKET'),
    ]
];
