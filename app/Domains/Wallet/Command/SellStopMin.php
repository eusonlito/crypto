<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopMin extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:min {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell-Stop After Reaching Sell Stop Min by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStopMin();
    }
}
