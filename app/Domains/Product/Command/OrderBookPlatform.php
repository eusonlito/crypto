<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class OrderBookPlatform extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:order-book:platform {--platform_id=}';

    /**
     * @var string
     */
    protected $description = 'Update Product Order Book By {--platform_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->platform();
        $this->factory()->action()->orderBook($this->platform);
    }
}
