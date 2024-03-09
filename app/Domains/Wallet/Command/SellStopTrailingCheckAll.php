<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopTrailingCheckAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:trailing:check:all';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell-Stop Trailing';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->sellStopTrailingCheckAll();
    }
}
