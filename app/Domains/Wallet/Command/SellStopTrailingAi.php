<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopTrailingAi extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:trailing:ai {--id=}';

    /**
     * @var string
     */
    protected $description = 'Generate Wallet Sell-Stop Trailing Using AI by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStopTrailingAi();
    }
}
