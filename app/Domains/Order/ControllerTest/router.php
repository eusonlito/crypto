<?php declare(strict_types=1);

namespace App\Domains\Order\ControllerTest;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth', 'test']], static function () {
    Route::get('/order/test/mail/filled', MailFilled::class)->name('order.test.mail.filled');
});
