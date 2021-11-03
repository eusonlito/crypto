<?php declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Domains\OPcache\Service\Preloader;

(new Preloader(__DIR__))
    ->paths(
        __DIR__.'/app',
        __DIR__.'/vendor/laravel',
    )
    ->ignore(
        'App\Domains\Shared\Test',
        'Illuminate\Http\Testing',
        'Illuminate\Filesystem\Cache',
        'Illuminate\Foundation\Testing',
        'Illuminate\Testing',
        'PHPUnit',
    )
    ->load();
