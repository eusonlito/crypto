<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'user.auth.redirect'], static function () {
    Route::any('/user/auth', AuthCredentials::class)->name('user.auth.credentials');
    Route::any('/user/signup', Signup::class)->name('user.signup');
});

Route::group(['middleware' => 'user.auth'], static function () {
    Route::any('/user/auth/tfa', AuthTFA::class)->name('user.auth.tfa');
    Route::get('/user/logout', Logout::class)->name('user.logout');
});

Route::group(['middleware' => 'user-auth'], static function () {
    Route::any('/user', Update::class)->name('user.update');
    Route::any('/user/platform', UpdatePlatform::class)->name('user.update.platform');
});
