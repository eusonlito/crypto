<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;

class SyncPlatform extends ActionAbstract
{
    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->platform();

        $this->syncOrder();
        $this->syncWallet();
        $this->syncOrder();
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = PlatformModel::query()
            ->byId($this->data['platform_id'])
            ->byUserId($this->auth->id)
            ->withUserPivot($this->auth->id)
            ->firstOrFail();
    }

    /**
     * @return void
     */
    protected function syncOrder(): void
    {
        $this->factory('Order')->action()->syncPlatform($this->platform);
    }

    /**
     * @return void
     */
    protected function syncWallet(): void
    {
        $this->factory('Wallet')->action()->syncPlatform($this->platform);
    }
}
