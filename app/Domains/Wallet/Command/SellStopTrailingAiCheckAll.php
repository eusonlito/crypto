<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopTrailingAiCheckAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:trailing:ai:check:all';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell-Stop Trailing AI';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->sellStopTrailingAiCheckAll();
    }
}
