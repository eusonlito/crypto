<?php declare(strict_types=1);

namespace App\Domains\Wallet\Schedule;

use App\Domains\Wallet\Command\BuyStopTrailingCheckAll as BuyStopTrailingCheckAllCommand;
use App\Domains\Wallet\Command\SellStopTrailingCheckAll as SellStopTrailingCheckAllCommand;
use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(SellStopTrailingCheckAllCommand::class, 'wallet-sell-stop-trailing-verify-all')->everyMinute();
        $this->command(BuyStopTrailingCheckAllCommand::class, 'wallet-buy-stop-trailing-verify-all')->everyMinute();
    }
}
