<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStopTrailingCheck extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop:trailing:check {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Sell-Stop Trailing by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStopTrailingCheck();
    }
}
