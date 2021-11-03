<?php declare(strict_types=1);

namespace App\Domains\Order\Command;

use App\Domains\Shared\Command\CommandAbstract as CommandAbstractShared;
use App\Domains\Order\Model\Order as Model;
use App\Domains\Product\Model\Product as ProductModel;

abstract class CommandAbstract extends CommandAbstractShared
{
    /**
     * @var \App\Domains\Order\Model\Order
     */
    protected Model $row;

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::findOrFail($this->checkOption('id'));
        $this->actingAs($this->row->user);
    }

    /**
     * @return \App\Domains\Product\Model\Product
     */
    protected function product(): ProductModel
    {
        return ProductModel::findOrFail($this->checkOption('product_id'));
    }

    /**
     * @return void
     */
    protected function auth()
    {
        $this->actingAs($this->checkOption('user_id'));
    }
}
