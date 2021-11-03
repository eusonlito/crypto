<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopMax extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:max {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell-Stop After Reaching Sell Stop Max by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStopMax();
    }
}
