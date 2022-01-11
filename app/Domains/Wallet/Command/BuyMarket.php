<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyMarket extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-market {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Market Reaching by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyMarket();
    }
}
