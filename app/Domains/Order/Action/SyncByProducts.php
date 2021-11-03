<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Order\Model\Order as Model;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\Order as OrderResource;

class SyncByProducts extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

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
        $this->platform = $products->first()->platform;

        if ($this->platformAvailable() === false) {
            return;
        }

        $this->api();
        $this->current($products);

        foreach ($products as $product) {
            foreach ($this->api->ordersProduct($product->code) as $each) {
                $this->store($product, $each);
            }
        }
    }

    /**
     * @return bool
     */
    protected function platformAvailable(): bool
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
     * @param \Illuminate\Support\Collection $products
     *
     * @return void
     */
    protected function current(Collection $products): void
    {
        $this->current = Model::byUserId($this->auth->id)
            ->byProductIds($products->pluck('id')->toArray())
            ->get()
            ->keyBy('code');
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return void
     */
    protected function store(ProductModel $product, OrderResource $resource): void
    {
        if ($row = $this->current->get($resource->id)) {
            $this->storeUpdate($row, $resource);
        } else {
            $this->storeCreate($product, $resource);
        }
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return void
     */
    protected function storeCreate(ProductModel $product, OrderResource $resource): void
    {
        Model::insert([
            'code' => $resource->id,

            'amount' => $resource->amount,
            'price' => $resource->price,
            'price_stop' => $resource->priceStop,
            'value' => $resource->value,
            'fee' => $resource->fee,

            'type' => $resource->type,
            'status' => $resource->status,
            'side' => $resource->side,

            'filled' => $resource->filled,

            'created_at' => $resource->createdAt,
            'updated_at' => $resource->updatedAt,

            'platform_id' => $this->platform->id,
            'product_id' => $product->id,
            'user_id' => $this->auth->id,
        ]);
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return void
     */
    protected function storeUpdate(Model $row, OrderResource $resource): void
    {
        $row->amount = $resource->amount;
        $row->price = $resource->price;
        $row->price_stop = $resource->priceStop;
        $row->value = $resource->value;
        $row->fee = $resource->fee;

        $row->type = $resource->type;
        $row->status = $resource->status;
        $row->side = $resource->side;

        $row->filled = $resource->filled;

        $row->updated_at = $resource->updatedAt;

        $row->save();
    }
}
