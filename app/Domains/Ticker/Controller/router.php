<?php declare(strict_types=1);

namespace App\Domains\Ticker\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/ticker', Index::class)->name('ticker.index');
    Route::any('/ticker/create', Create::class)->name('ticker.create');
    Route::any('/ticker/{id}', Update::class)->name('ticker.update');
    Route::post('/ticker/{id}/boolean/{column}', UpdateBoolean::class)->name('ticker.update.boolean');
});
