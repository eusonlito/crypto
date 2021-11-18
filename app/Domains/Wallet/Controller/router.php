<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/wallet', Index::class)->name('wallet.index');
    Route::any('/wallet/create', Create::class)->name('wallet.create');
    Route::any('/wallet/{id}', Update::class)->name('wallet.update');
    Route::any('/wallet/{id}/buy-stop', UpdateBuyStop::class)->name('wallet.update.buy-stop');
    Route::any('/wallet/{id}/sell-stop', UpdateSellStop::class)->name('wallet.update.sell-stop');
    Route::post('/wallet/{id}/column/{column}', UpdateColumn::class)->name('wallet.update.column');
    Route::post('/wallet/{id}/boolean/{column}', UpdateBoolean::class)->name('wallet.update.boolean');
});
