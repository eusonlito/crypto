<?php declare(strict_types=1);

namespace App\Domains\Maintenance\Action;

use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @return void
     */
    public function fileDeleteOlder(): void
    {
        $this->actionHandle(FileDeleteOlder::class, $this->validate()->fileDeleteOlder());
    }

    /**
     * @return void
     */
    public function fileZip(): void
    {
        $this->actionHandle(FileZip::class, $this->validate()->fileZip());
    }

    /**
     * @return array
     */
    public function opcachePreload(): array
    {
        return $this->actionHandle(OpcachePreload::class);
    }
}
