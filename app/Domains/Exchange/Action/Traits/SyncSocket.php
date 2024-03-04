<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action\Traits;

use Throwable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Services\Platform\Resource\Exchange as ExchangeResource;

trait SyncSocket
{
    use SyncRelation;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var int
     */
    protected int $timestamp;

    /**
     * @var array
     */
    protected array $cache = [];

    /**
     * @return void
     */
    protected function reload(): void
    {
        $this->timestamp();
        $this->products();
    }

    /**
     * @return void
     */
    protected function timestamp(): void
    {
        $this->timestamp = time();
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProductModel::query()
            ->byPlatformId($this->platform->id)
            ->whereTrade()
            ->whereCrypto()
            ->whereWallet()
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function read(): void
    {
        while (true) {
            try {
                $this->connect();
            } catch (Throwable $e) {
                $this->reconnect($e);
            }

            $this->sleep();
        }
    }

    /**
     * @return void
     */
    protected function sleep(): void
    {
        sleep($this->products->firstWhere('tracking') ? 2 : 5);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reconnect(Throwable $e): void
    {
        report($e);

        $this->connect();
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return void
     */
    protected function readValue(ExchangeResource $resource): void
    {
        $this->readValueReload();

        if ($this->readValueIsValid($resource)) {
            $this->relations($this->store($resource));
        }
    }

    /**
     * @return void
     */
    protected function readValueReload(): void
    {
        if ((time() - $this->timestamp) >= 60) {
            $this->reload();
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return bool
     */
    protected function readValueIsValid(ExchangeResource $resource): bool
    {
        $product = $this->products->get($resource->code);

        if ($product === null) {
            return false;
        }

        if ($product->tracking) {
            return (bool)$this->toCache($resource);
        }

        if (($previous = $this->fromCache($resource)) === null) {
            return (bool)$this->toCache($resource);
        }

        if ($resource->shouldBeUpdated($resource, $previous->price, $previous->createdAt)) {
            return (bool)$this->toCache($resource);
        }

        return false;
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return ?\App\Services\Platform\Resource\Exchange
     */
    protected function fromCache(ExchangeResource $resource): ?ExchangeResource
    {
        return $this->cache[$resource->code] ?? null;
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return \App\Services\Platform\Resource\Exchange
     */
    protected function toCache(ExchangeResource $resource): ExchangeResource
    {
        return $this->cache[$resource->code] = $resource;
    }

    /**
     * @param \App\Services\Platform\Resource\Exchange $resource
     *
     * @return \App\Domains\Exchange\Model\Exchange
     */
    protected function store(ExchangeResource $resource): Model
    {
        return Model::query()->create([
            'exchange' => $resource->price,
            'platform_id' => $this->platform->id,
            'product_id' => $this->products->get($resource->code)->id,
        ]);
    }
}
