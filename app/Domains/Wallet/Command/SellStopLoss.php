<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopLoss extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop-loss {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell StopLoss After Reaching Sell StopLoss At by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStopLoss();
    }
}
