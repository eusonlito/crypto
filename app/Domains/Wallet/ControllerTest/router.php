<?php declare(strict_types=1);

namespace App\Domains\Wallet\ControllerTest;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth', 'test']], static function () {
    Route::get('/wallet/test/sell-stop-min-mail', SellStopMinMail::class)->name('wallet.test.sell-stop.min.mail');
});
