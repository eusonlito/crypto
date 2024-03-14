<?php declare(strict_types=1);

namespace App\Domains\Wallet\ControllerTest;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth', 'test']], static function () {
    Route::get('/wallet/test/mail/sell-stop-min', MailSellStopMin::class)->name('wallet.test.mail.sell-stop.min');
});
