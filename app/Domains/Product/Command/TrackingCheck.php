<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class TrackingCheck extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:tracking:check';

    /**
     * @var string
     */
    protected $description = 'Update tracking status from Wallets';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->trackingCheck();
    }
}
