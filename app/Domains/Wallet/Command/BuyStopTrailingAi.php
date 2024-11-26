<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingAi extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:ai {--id=}';

    /**
     * @var string
     */
    protected $description = 'Generate Wallet Buy-Stop Trailing Using AI by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopTrailingAi();
    }
}
