<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class OrderBookAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:order-book:all';

    /**
     * @var string
     */
    protected $description = 'Update Product Order Book From All Platforms';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->orderBookAll();
    }
}
