<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class SyncAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:sync:all';

    /**
     * @var string
     */
    protected $description = 'Update Product Data From All Platforms';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->syncAll();
    }
}
