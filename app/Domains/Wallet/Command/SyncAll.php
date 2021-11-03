<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SyncAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sync:all {--user_id=}';

    /**
     * @var string
     */
    protected $description = 'Update Wallet Data by {--user_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->actingAs($this->checkOption('user_id'));
        $this->factory()->action()->syncAll();
    }
}
