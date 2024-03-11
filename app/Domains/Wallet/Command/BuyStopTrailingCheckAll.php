<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingCheckAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:check:all';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop Trailing';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->buyStopTrailingCheckAll();
    }
}
