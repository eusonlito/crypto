<?php declare(strict_types=1);

namespace App\Domains\Currency\Command;

use App\Domains\Core\Command\CommandAbstract;

class SyncAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'currency:sync:all';

    /**
     * @var string
     */
    protected $description = 'Update Currency Data From All Platforms';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->syncAll();
    }
}
