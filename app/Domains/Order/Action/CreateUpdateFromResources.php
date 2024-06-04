<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;
use App\Services\Platform\Resource\Order as OrderResource;

class CreateUpdateFromResources extends ActionAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $wallets;

    /**
     * @var array
     */
    protected array $resources;

    /**
     * @param array $resources
     *
     * @return void
     */
    public function handle(array $resources): void
    {
        if (empty($resources)) {
            return;
        }

        $this->resources = $resources;

        $this->current();
        $this->products();
        $this->wallets();
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function current(): void
    {
        $this->current = Model::query()
            ->byUserId($this->auth->id)
            ->byCodes(array_column($this->resources, 'id'))
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProductModel::query()
            ->byPlatformId($this->data['platform_id'])
            ->pluck('id', 'code');
    }

    /**
     * @return void
     */
    protected function wallets(): void
    {
        $this->wallets = WalletModel::query()
            ->byUserId($this->auth->id)
            ->byPlatformId($this->data['platform_id'])
            ->byProductIds($this->products->all())
            ->pluck('id', 'product_id');
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->resources as $resource) {
            $this->store($resource);
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return void
     */
    protected function store(OrderResource $resource): void
    {
        $row = $this->storeCreateOrUpdate($resource);

        if (empty($row)) {
            return;
        }

        $this->storeMail($row);
    }

    /**
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return ?\App\Domains\Order\Model\Order
     */
    protected function storeCreateOrUpdate(OrderResource $resource): ?Model
    {
        return ($row = $this->current->get($resource->id))
            ? $this->storeUpdate($row, $resource)
            : $this->storeCreate($resource);
    }

    /**
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return ?\App\Domains\Order\Model\Order
     */
    protected function storeCreate(OrderResource $resource): ?Model
    {
        $product_id = $this->products->get($resource->product);

        if (empty($product_id)) {
            return null;
        }

        $row = Model::query()->create([
            'code' => $resource->id,
            'reference' => $resource->reference,

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

            'platform_id' => $this->data['platform_id'],
            'product_id' => $product_id,
            'wallet_id' => $this->wallets->get($product_id),
            'user_id' => $this->auth->id,
        ]);

        $row->updatePrevious();
        $row->save();

        return $row;
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     * @param \App\Services\Platform\Resource\Order $resource
     *
     * @return \App\Domains\Order\Model\Order
     */
    protected function storeUpdate(Model $row, OrderResource $resource): Model
    {
        $row->reference = $resource->reference;
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

        $row->updatePrevious();

        $row->save();

        return $row;
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return void
     */
    protected function storeMail(Model $row): void
    {
        if ($this->storeMailAvailable($row) === false) {
            return;
        }

        $this->factory()->mail()->filled($row, $this->storeMailPrevious($row));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function storeMailPrevious(Model $row): Collection
    {
        return Model::query()
            ->byIdPrevious($row->id)
            ->byUserId($this->auth->id)
            ->byProductId($row->product_id)
            ->whereFilled()
            ->orderByLast()
            ->limit(5)
            ->get();
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return bool
     */
    protected function storeMailAvailable(Model $row): bool
    {
        if (empty($row->filled)) {
            return false;
        }

        if (time() > strtotime($row->updated_at.' +1 hour')) {
            return false;
        }

        return $row->wasRecentlyCreated
            || $row->wasChanged('filled');
    }
}
