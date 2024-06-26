<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;
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
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected WalletModel $wallet;

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return \App\Domains\Order\Model\Order
     */
    public function handle(ProductModel $product): Model
    {
        $this->product($product);
        $this->platform();
        $this->wallet();

        $this->api();
        $this->cancelOpen();
        $this->send();
        $this->save();

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
    protected function wallet(): void
    {
        $this->wallet = WalletModel::query()
            ->byUserId($this->auth->id)
            ->byId($this->data['wallet_id'])
            ->byProductId($this->product->id)
            ->byPlatformId($this->platform->id)
            ->firstOrFail();
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
        $this->factory()->action()->cancelOpen($this->product);
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
                'amount' => $this->orderAmount(),
                'price' => $this->orderPrice(),
                'limit' => $this->orderLimit(),
                'trailing' => $this->data['trailing'],
            ],
            $this->data['reference'],
        );
    }

    /**
     * @return float
     */
    protected function orderAmount(): float
    {
        return helper()->roundFixed($this->data['amount'], $this->product->quantity_decimal);
    }

    /**
     * @return float
     */
    protected function orderPrice(): float
    {
        return helper()->roundFixed($this->data['price'], $this->product->price_decimal);
    }

    /**
     * @return float
     */
    protected function orderLimit(): float
    {
        return helper()->roundFixed($this->data['limit'], $this->product->price_decimal);
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveRow();
        $this->savePrevious();
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row = Model::query()->create([
            'code' => $this->resource->id,
            'reference' => $this->resource->reference,

            'amount' => $this->resource->amount,
            'price' => $this->resource->price,
            'price_stop' => $this->resource->priceStop,
            'value' => $this->resource->value,
            'fee' => $this->resource->fee,

            'type' => $this->resource->type,
            'status' => $this->resource->status,
            'side' => $this->resource->side,

            'filled' => $this->resource->filled,

            'created_at' => $this->resource->updatedAt,
            'updated_at' => $this->resource->updatedAt,

            'platform_id' => $this->platform->id,
            'product_id' => $this->product->id,
            'user_id' => $this->auth->id,
            'wallet_id' => $this->wallet->id,
        ]);
    }

    /**
     * @return void
     */
    protected function savePrevious(): void
    {
        $this->row->updatePrevious();
        $this->row->save();
    }
}
