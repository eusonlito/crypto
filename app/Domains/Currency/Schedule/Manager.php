<?php declare(strict_types=1);

namespace App\Domains\Currency\Schedule;

use App\Domains\Currency\Command\SyncAll as SyncAllCommand;
use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(SyncAllCommand::class, 'currency-sync-all')->hourly();
    }
}
