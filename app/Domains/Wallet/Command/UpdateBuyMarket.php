<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class UpdateBuyMarket extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:update:buy:market {--id=} {--value=} {--retry=}';

    /**
     * @var string
     */
    protected $description = 'Send a Buy Market Order to Wallet by {--id=} and {--value=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->requestWithOptions();
        $this->factory()->action()->updateBuyMarket();
    }
}
