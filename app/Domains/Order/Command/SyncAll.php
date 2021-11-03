<?php declare(strict_types=1);

namespace App\Domains\Order\Command;

class SyncAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'order:sync:all {--user_id=}';

    /**
     * @var string
     */
    protected $description = 'Update Order Data by {--user_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->auth();
        $this->factory()->action()->syncAll();
    }
}
