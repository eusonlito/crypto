<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Action;

use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @return void
     */
    public function sync(): void
    {
        $this->actionHandle(Sync::class, $this->validate()->sync());
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
        $this->actionHandle(SyncPlatform::class, $this->validate()->syncPlatform());
    }
}
