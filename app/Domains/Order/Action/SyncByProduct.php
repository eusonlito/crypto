<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Product\Model\Product as ProductModel;

class SyncByProduct extends ActionAbstract
{
    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return void
     */
    public function handle(ProductModel $product): void
    {
        $this->factory()->action()->syncByProducts(collect([$product]));
    }
}
