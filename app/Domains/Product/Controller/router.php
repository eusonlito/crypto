<?php declare(strict_types=1);

namespace App\Domains\Product\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/product', Index::class)->name('product.index');
    Route::post('/product/{id}/favorite', Favorite::class)->name('product.favorite');
    Route::post('/product/{id}/boolean/{column}', UpdateBoolean::class)->name('product.update.boolean');
});
