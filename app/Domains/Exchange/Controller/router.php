<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/exchange', Index::class)->name('exchange.index');
    Route::get('/exchange/{product_id}', Detail::class)->name('exchange.detail');
});
