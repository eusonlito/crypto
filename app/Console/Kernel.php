<?php declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as KernelVendor;
use App\Domains\CoreMaintenance\Schedule\Manager as CoreMaintenanceScheduleManager;
use App\Domains\Currency\Schedule\Manager as CurrencyScheduleManager;
use App\Domains\Exchange\Schedule\Manager as ExchangeScheduleManager;
use App\Domains\Product\Schedule\Manager as ProductScheduleManager;

class Kernel extends KernelVendor
{
    /**
     * @return void
     */
    protected function commands()
    {
        foreach (glob(app_path('Domains/*/Command')) as $dir) {
            $this->load($dir);
        }
    }

    /**
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        (new CurrencyScheduleManager($schedule))->handle();
        (new ExchangeScheduleManager($schedule))->handle();
        (new ProductScheduleManager($schedule))->handle();
        (new CoreMaintenanceScheduleManager($schedule))->handle();

        $this->scheduleCachePrune($schedule);
    }

    /**
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function scheduleCachePrune(Schedule $schedule): void
    {
        $schedule->command('cache:prune-stale-tags')->hourly();
    }
}
