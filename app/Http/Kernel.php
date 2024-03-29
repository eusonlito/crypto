<?php declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as KernelVendor;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Session\Middleware\StartSession;
use App\Domains\Language\Middleware\Request as LanguageRequest;
use App\Domains\User\Middleware\Admin as UserAdmin;
use App\Domains\User\Middleware\Auth as UserAuth;
use App\Domains\User\Middleware\AuthRedirect as UserAuthRedirect;
use App\Domains\User\Middleware\AuthTFA as UserAuthTFA;
use App\Http\Middleware\MessagesShareFromSession;
use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\Reset;
use App\Http\Middleware\Test;

class Kernel extends KernelVendor
{
    /**
     * @var array<int, string>
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        TrimStrings::class,
        RequestLogger::class,
        Reset::class,
        MessagesShareFromSession::class,
        LanguageRequest::class,
    ];

    /**
     * @var array<string, array<int, string>>
     */
    protected $middlewareGroups = [
        'user-auth' => [
            UserAuth::class,
            UserAuthTFA::class,
        ],
    ];

    /**
     * @var array<string, string>
     */
    protected $middlewareAliases = [
        'test' => Test::class,
        'user.admin' => UserAdmin::class,
        'user.auth' => UserAuth::class,
        'user.auth.redirect' => UserAuthRedirect::class,
        'user.auth.tfa' => UserAuthTFA::class,
    ];
}
