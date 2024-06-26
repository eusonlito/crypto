<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;

    /**
     * @return void
     */
    public function cancelOpen(): void
    {
        $this->actionHandle(CancelOpen::class, [], ...func_get_args());
    }

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
    public function createUpdateFromResources(): void
    {
        $this->actionHandle(CreateUpdateFromResources::class, $this->validate()->createUpdateFromResources(), ...func_get_args());
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
    public function syncPlatform(): void
    {
        $this->actionHandle(SyncPlatform::class, [], ...func_get_args());
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

    /**
     * @return void
     */
    public function walletFix(): void
    {
        $this->actionHandle(WalletFix::class);
    }
}
