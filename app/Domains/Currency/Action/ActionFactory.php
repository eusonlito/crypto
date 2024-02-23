<?php declare(strict_types=1);

namespace App\Domains\Currency\Action;

use App\Domains\Currency\Model\Currency as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Currency\Model\Currency
     */
    protected ?Model $row;

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
