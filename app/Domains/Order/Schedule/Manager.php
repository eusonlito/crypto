<?php declare(strict_types=1);

namespace App\Domains\Order\Schedule;

use App\Domains\Order\Command\WalletFix as WalletFixCommand;
use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(WalletFixCommand::class, 'order-wallet-fix')->hourly();
    }
}
