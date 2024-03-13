<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\ApiFactoryAbstract;

class Sync extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        if ($this->available() === false) {
            return;
        }

        $this->api();

        if ($this->apiAvailable()) {
            $this->saveAll();
        } else {
            $this->saveByWallets();
        }
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return bool
     */
    protected function apiAvailable(): bool
    {
        return $this->api->ordersAllAvailable();
    }

    /**
     * @return void
     */
    protected function saveByWallets(): void
    {
        $this->factory()->action()->syncByWallets($this->platform);
    }

    /**
     * @return void
     */
    protected function saveAll(): void
    {
        $this->factory()
            ->action(['platform_id' => $this->platform->id])
            ->createUpdateFromResources($this->api->ordersAll()->all());
    }
}
