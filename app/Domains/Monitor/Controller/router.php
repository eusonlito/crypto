<?php declare(strict_types=1);

namespace App\Domains\Monitor\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth', 'user.admin']], static function () {
    Route::get('/monitor', Index::class)->name('monitor.index');
    Route::get('/monitor/database', Database::class)->name('monitor.database');
    Route::get('/monitor/installation', Installation::class)->name('monitor.installation');
    Route::get('/monitor/log', Log::class)->name('monitor.log');
});
