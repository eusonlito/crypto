<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

use App\Domains\Shared\Command\CommandAbstract;

class FiatAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:fiat:all';

    /**
     * @var string
     */
    protected $description = 'Added FIAT Products From All Platforms';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->fiatAll();
    }
}
