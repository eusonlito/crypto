<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\ApiFactoryAbstract;

class SyncByProducts extends ActionAbstract
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
     * @param \Illuminate\Support\Collection $products
     *
     * @return void
     */
    public function handle(Collection $products): void
    {
        $this->products($products);
        $this->iterate();
    }

    /**
     * @param \Illuminate\Support\Collection $products
     *
     * @return void
     */
    protected function products(Collection $products): void
    {
        $this->products = $products
            ->where('crypto', true)
            ->groupBy('platform_id');
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->products as $each) {
            $this->platform($each);
        }
    }

    /**
     * @param \Illuminate\Support\Collection $products
     *
     * @return void
     */
    protected function platform(Collection $products): void
    {
        $platform = $products->first()->platform;

        if ($platform->userPivotLoad($this->auth) === false) {
            return;
        }

        $api = ProviderApiFactory::get($platform);
        $resources = [];

        foreach ($products as $product) {
            $resources[] = $api->ordersProduct($product->code)->all();
        }

        if (empty($resources)) {
            return;
        }

        $this->factory()
            ->action(['platform_id' => $platform->id])
            ->createUpdateFromResources(array_merge(...$resources));
    }
}
