<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use Illuminate\Support\Collection;
use App\Domains\Exchange\Action\Traits\SyncRelation as SyncRelationTrait;
use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Platform\Service\Provider\ProviderSocketFactory;
use App\Services\Platform\Resource\Exchange as ExchangeResource;

class Sync extends ActionAbstract
{
    use SyncRelationTrait;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        if (ProviderSocketFactory::get($platform, 'Ticker') !== null) {
            return;
        }

        $this->platform = $platform;

        $this->products();
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProductModel::query()
            ->byPlatformId($this->platform->id)
            ->enabled()
            ->whereTrade()
            ->whereCrypto()
            ->whereWallet()
            ->withExchange()
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach (ProviderApiFactory::get($this->platform)->exchanges() as $each) {
            if ($row = $this->store($each)) {
                $this->relations($row);
            }
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return ?\App\Domains\Exchange\Model\Exchange
     */
    protected function store(ExchangeResource $resource): ?Model
    {
        $product = $this->products->get($resource->code);

        if ($this->storeIsValid($resource, $product) === false) {
            return null;
        }

        return Model::query()->create([
            'exchange' => $resource->price,
            'platform_id' => $this->platform->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     * @param ?\App\Domains\Product\Model\Product $product
     *
     * @return bool
     */
    protected function storeIsValid(ExchangeResource $resource, ?ProductModel $product): bool
    {
        if ($product === null) {
            return false;
        }

        if ($product->tracking) {
            return true;
        }

        if (($previous = $product->exchange) === null) {
            return true;
        }

        return $resource->shouldBeUpdated($resource, $previous->exchange, $previous->created_at);
    }
}
