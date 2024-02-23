<?php declare(strict_types=1);

namespace App\Domains\CoreMaintenance\Controller;

use Illuminate\Support\Facades\Route;

Route::get('/core-maintenance/opcache/preload', OpcachePreload::class)->name('core-maintenance.opcache.preload');
