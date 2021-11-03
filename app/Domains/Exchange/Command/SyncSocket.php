<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

class SyncSocket extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'exchange:sync:socket {--platform_id=}';

    /**
     * @var string
     */
    protected $description = 'Sync Exchange as Socket form {--platform_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->syncSocket($this->platform());
    }
}
