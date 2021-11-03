<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopMax extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:max {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop After Reaching Buy Stop Max by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopMax();
    }
}
