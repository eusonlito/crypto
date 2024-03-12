<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingFollow extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:follow {--id=}';

    /**
     * @var string
     */
    protected $description = 'Follow Wallet Buy-Stop Trailing by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopTrailingFollow();
    }
}
