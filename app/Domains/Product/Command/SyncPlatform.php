<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class SyncPlatform extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:sync:platform {--platform_id=}';

    /**
     * @var string
     */
    protected $description = 'Update Product Data By {--platform_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->platform();
        $this->factory()->action()->sync($this->platform);
    }
}
