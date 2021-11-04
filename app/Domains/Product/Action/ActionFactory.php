<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Product\Model\Product as Model;
use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Product\Model\Product
     */
    protected ?Model $row;

    /**
     * @return void
     */
    public function fiatAll(): void
    {
        $this->actionHandle(FiatAll::class);
    }

    /**
     * @return \App\Domains\Product\Model\Product
     */
    public function favorite(): ?Model
    {
        return $this->actionHandle(Favorite::class);
    }

    /**
     * @return void
     */
    public function fiat(): void
    {
        $this->actionHandle(Fiat::class, [], ...func_get_args());
    }

    /**
     * @return void
     */
    public function orderBookAll(): void
    {
        $this->actionHandle(OrderBookAll::class);
    }

    /**
     * @return void
     */
    public function orderBook(): void
    {
        $this->actionHandle(OrderBook::class, [], ...func_get_args());
    }

    /**
     * @return \App\Domains\Product\Model\Product
     */
    public function updateBoolean(): Model
    {
        return $this->actionHandle(UpdateBoolean::class, $this->validate()->updateBoolean());
    }

    /**
     * @return void
     */
    public function syncAll(): void
    {
        $this->actionHandle(SyncAll::class);
    }

    /**
     * @return void
     */
    public function sync(): void
    {
        $this->actionHandle(Sync::class, [], ...func_get_args());
    }
}
