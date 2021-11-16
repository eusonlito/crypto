<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class BuyStop extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:buy-stop {--id=}';

    /**
     * @var string
     */
    protected $description = 'Create a Buy-Stop Order for Wallet {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->buyStop();
    }
}
