<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/', Index::class)->name('dashboard.index');
    Route::get('/start', Start::class)->name('dashboard.start');
    Route::any('/sync', Sync::class)->name('dashboard.sync');
});
