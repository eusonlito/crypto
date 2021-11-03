<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Action;

use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @return void
     */
    public function sync(): void
    {
        $this->actionHandle(Sync::class);
    }
}
