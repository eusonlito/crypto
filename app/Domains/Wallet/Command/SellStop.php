<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SellStop extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sell-stop {--id=}';

    /**
     * @var string
     */
    protected $description = 'Create a sell-stop order for wallet {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();
        $this->factory()->action()->sellStop();
    }
}
