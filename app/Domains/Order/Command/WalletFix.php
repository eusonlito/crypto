<?php declare(strict_types=1);

namespace App\Domains\Order\Command;

class WalletFix extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'order:wallet:fix';

    /**
     * @var string
     */
    protected $description = 'Fix Wallet Relation';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->walletFix();
    }
}
