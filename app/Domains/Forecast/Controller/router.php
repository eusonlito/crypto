<?php declare(strict_types=1);

namespace App\Domains\Forecast\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/forecast', Index::class)->name('forecast.index');
    Route::any('/forecast/future', Future::class)->name('forecast.future');
});
