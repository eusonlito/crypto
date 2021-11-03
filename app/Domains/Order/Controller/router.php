<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user-auth'], static function () {
    Route::get('/order', Index::class)->name('order.index');
    Route::any('/order/status', Status::class)->name('order.status');
    Route::any('/order/sync', Sync::class)->name('order.sync');
});
