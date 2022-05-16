<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\Order\Model\Order
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create(), ...func_get_args());
    }

    /**
     * @return \App\Domains\Order\Model\Order
     */
    public function createSimple(): Model
    {
        return $this->actionHandle(CreateSimple::class, $this->validate()->createSimple());
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

    /**
     * @return void
     */
    public function syncByProduct(): void
    {
        $this->actionHandle(SyncByProduct::class, [], ...func_get_args());
    }

    /**
     * @return void
     */
    public function syncByProducts(): void
    {
        $this->actionHandle(SyncByProducts::class, [], ...func_get_args());
    }

    /**
     * @return void
     */
    public function syncByWallets(): void
    {
        $this->actionHandle(SyncByWallets::class, [], ...func_get_args());
    }

    /**
     * @return \App\Domains\Order\Model\Order
     */
    public function update(): Model
    {
        return $this->actionHandle(Update::class, $this->validate()->update());
    }
}
