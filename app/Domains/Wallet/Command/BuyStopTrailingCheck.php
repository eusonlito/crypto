<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingCheck extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:check {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop Trailing by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopTrailingCheck();
    }
}
