<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStopTrailingAiCheck extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop:trailing:ai:check {--id=}';

    /**
     * @var string
     */
    protected $description = 'Check Wallet Buy-Stop Trailing AI by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStopTrailingAiCheck();
    }
}
