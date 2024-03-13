<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Services\Platform\ApiFactoryAbstract;

class SyncByWallets extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

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

        $this->products();
        $this->api();
        $this->save();
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
    protected function products(): void
    {
        $this->products = ProductModel::query()
            ->byPlatformId($this->platform->id)
            ->whereWalletsByUserId($this->auth->id)
            ->pluck('code');
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $resources = [];

        foreach ($this->products as $code) {
            $resources[] = $this->api->ordersProduct($code)->all();
        }

        if (empty($resources)) {
            return;
        }

        $this->factory()
            ->action(['platform_id' => $this->platform->id])
            ->createUpdateFromResources(array_merge(...$resources));
    }
}
