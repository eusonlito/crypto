<?php declare(strict_types=1);

namespace App\Domains\Product\Schedule;

use App\Domains\Product\Command\SyncAll as SyncAllCommand;
use App\Domains\Product\Command\TrackingCheck as TrackingCheckCommand;
use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(SyncAllCommand::class, 'product-sync-all')->hourly();
        $this->command(TrackingCheckCommand::class, 'product-tracking-check')->hourly();
    }
}
