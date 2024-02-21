<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Order\Model\Order as Model;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\Order as OrderResource;

class SyncByWallets extends ActionAbstract
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
        $this->current();
        $this->products();
        $this->iterate();
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
    protected function current(): void
    {
        $this->current = Model::byUserId($this->auth->id)
            ->byPlatformId($this->platform->id)
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProductModel::byPlatformId($this->platform->id)
            ->whereWalletsByUserId($this->auth->id)
            ->get()
            ->keyBy('code');
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
    protected function iterate(): void
    {
        foreach ($this->products as $product) {
            foreach ($this->api->ordersProduct($product->code) as $each) {
                $this->store($product, $each);
            }
        }
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

            'created_at' => $resource->updatedAt,
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

        $row->created_at = $resource->updatedAt;
        $row->updated_at = $resource->updatedAt;

        $row->save();
    }
}
