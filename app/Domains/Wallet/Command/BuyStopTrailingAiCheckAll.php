<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingAiCheckAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:ai:check:all';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop Trailing AI';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->buyStopTrailingAiCheckAll();
    }
}
