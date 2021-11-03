<?php declare(strict_types=1);

namespace App\Domains\Exchange\Schedule;

use App\Domains\Exchange\Command\ClearOld as ClearOldCommand;
use App\Domains\Shared\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(ClearOldCommand::class, 'exchange-clear-old')->dailyAt('00:15');
    }
}
