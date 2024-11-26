<?php declare(strict_types=1);

namespace App\Domains\Wallet\Schedule;

use App\Domains\Wallet\Command\BuyStopTrailingCheckAll as BuyStopTrailingCheckAllCommand;
use App\Domains\Wallet\Command\BuyStopTrailingAiCheckAll as BuyStopTrailingCheckAiAllCommand;
use App\Domains\Wallet\Command\SellStopTrailingCheckAll as SellStopTrailingCheckAllCommand;
use App\Domains\Wallet\Command\SellStopTrailingAiCheckAll as SellStopTrailingCheckAiAllCommand;
use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->command(SellStopTrailingCheckAllCommand::class, 'wallet-sell-stop-trailing-check-all')
            ->everyMinute();

        $this->command(BuyStopTrailingCheckAllCommand::class, 'wallet-buy-stop-trailing-check-all')
            ->everyMinute();

        $this->command(SellStopTrailingCheckAiAllCommand::class, 'wallet-sell-stop-trailing-ai-check-all')
            ->everyThirtyMinutes();

        $this->command(BuyStopTrailingCheckAiAllCommand::class, 'wallet-buy-stop-trailing-ai-check-all')
            ->everyThirtyMinutes();
    }
}
