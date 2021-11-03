<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\Order as OrderResource;

class Create extends ActionAbstract
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
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var \App\Services\Platform\Resource\Order
     */
    protected OrderResource $resource;

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return \App\Domains\Order\Model\Order
     */
    public function handle(ProductModel $product): Model
    {
        $this->product($product);
        $this->platform();

        $this->api();
        $this->cancelOpen();
        $this->send();
        $this->create();

        return $this->row;
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return void
     */
    protected function product(ProductModel $product): void
    {
        $this->product = $product;
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->product->platform;
        $this->platform->userPivotLoad($this->auth);
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
    protected function cancelOpen(): void
    {
        if ($this->api->ordersOpen($this->product->code)->isNotEmpty()) {
            $this->api->ordersCancelAll($this->product->code);
        }
    }

    /**
     * @return void
     */
    protected function send(): void
    {
        $this->resource = $this->api->orderCreate(
            $this->product->code,
            $this->data['side'],
            $this->data['type'],
            [
                'amount' => helper()->roundFixed($this->data['amount'], $this->product->quantity_decimal),
                'price' => helper()->roundFixed($this->data['price'], $this->product->price_decimal),
                'limit' => helper()->roundFixed($this->data['limit'], $this->product->price_decimal),
            ]
        );
    }

    /**
     * @return void
     */
    protected function create(): void
    {
        $this->row = Model::create([
            'code' => $this->resource->id,

            'amount' => $this->resource->amount,
            'price' => $this->resource->price,
            'price_stop' => $this->resource->priceStop,
            'value' => $this->resource->value,
            'fee' => $this->resource->fee,

            'type' => $this->resource->type,
            'status' => $this->resource->status,
            'side' => $this->resource->side,

            'filled' => $this->resource->filled,

            'created_at' => $this->resource->createdAt,
            'updated_at' => $this->resource->updatedAt,

            'platform_id' => $this->platform->id,
            'product_id' => $this->product->id,
            'user_id' => $this->auth->id,
        ]);
    }
}
