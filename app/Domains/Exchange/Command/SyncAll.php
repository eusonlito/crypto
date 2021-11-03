<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

class SyncAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'exchange:sync:all';

    /**
     * @var string
     */
    protected $description = 'Update Exchange Data From All Platforms';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->syncAll();
    }
}
