<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;
use App\Services\Platform\Resource\Order as OrderResource;

class ApiFactory extends ApiFactoryAbstract
{
    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->handle(Check::class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function currencies(): Collection
    {
        return $this->handle(Currencies::class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function exchanges(): Collection
    {
        return $this->handle(Exchanges::class);
    }

    /**
     * @param string $symbol
     *
     * @return \App\Services\Platform\Resource\OrderBook
     */
    public function orderBook(string $symbol): OrderBookResource
    {
        return $this->handle(OrderBook::class, $symbol);
    }

    /**
     * @param string $product
     * @param string $side
     * @param string $type
     * @param array $data
     *
     * @return \App\Services\Platform\Resource\Order
     */
    public function orderCreate(string $product, string $side, string $type, array $data): OrderResource
    {
        return $this->handle(OrderCreate::class, $product, $side, $type, $data);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function ordersAll(): Collection
    {
        return $this->handle(OrdersAll::class);
    }

    /**
     * @return bool
     */
    public function ordersAllAvailable(): bool
    {
        return true;
    }

    /**
     * @param string $product
     *
     * @return void
     */
    public function ordersCancelAll(string $product): void
    {
        $this->handle(OrdersCancelAll::class, $product);
    }

    /**
     * @param ?string $product = null
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersOpen(?string $product = null): Collection
    {
        return $this->handle(OrdersOpen::class, $product);
    }

    /**
     * @param string $product
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersProduct(string $product): Collection
    {
        return $this->handle(OrdersProduct::class, $product);
    }

    /**
     * @param bool $filter
     *
     * @return \Illuminate\Support\Collection
     */
    public function products(bool $filter): Collection
    {
        return $this->handle(Products::class, $filter);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function wallets(): Collection
    {
        return $this->handle(Wallets::class);
    }
}
