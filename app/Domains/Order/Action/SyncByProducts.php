<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Throwable;
use Illuminate\Support\Collection;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Services\Platform\ApiFactoryAbstract;

class SyncByProducts extends ActionAbstract
{
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
        $this->products = $products->groupBy('platform_id');
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
            $resources[] = $this->platformProduct($api, $product);
        }

        $resources = array_filter(array_merge(...$resources));

        if (empty($resources)) {
            return;
        }

        $this->factory()
            ->action(['platform_id' => $platform->id])
            ->createUpdateFromResources($resources);
    }

    /**
     * @param \App\Services\Platform\ApiFactoryAbstract $api
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return array
     */
    protected function platformProduct(ApiFactoryAbstract $api, ProductModel $product): array
    {
        if ($product->currency_base_id === $product->currency_quote_id) {
            return [];
        }

        try {
            return $api->ordersProduct($product->code)->all();
        } catch (Throwable $e) {
            report($e);
        }

        return [];
    }
}
