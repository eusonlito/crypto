<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

class SyncOne extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'wallet:sync:one {--id=}';

    /**
     * @var string
     */
    protected $description = 'Update Wallet Data by {--id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->row();

        $this->actingAs($this->row->user_id);
        $this->factory()->action()->syncOne();
    }
}
