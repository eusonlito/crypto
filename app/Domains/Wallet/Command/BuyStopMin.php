<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopMin extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:min {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop After Reaching Buy Stop Min by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopMin();
    }
}
