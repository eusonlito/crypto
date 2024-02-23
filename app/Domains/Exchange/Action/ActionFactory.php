<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Exchange\Model\Exchange
     */
    protected ?Model $row;

    /**
     * @return void
     */
    public function clearOld(): void
    {
        $this->actionHandle(ClearOld::class, $this->validate()->clearOld());
    }

    /**
     * @return void
     */
    public function tickerSocket(): void
    {
        $this->actionHandle(TickerSocket::class, [], ...func_get_args());
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
    public function syncAll(): void
    {
        $this->actionHandle(SyncAll::class);
    }

    /**
     * @return void
     */
    public function syncSocket(): void
    {
        $this->actionHandle(SyncSocket::class, [], ...func_get_args());
    }
}
