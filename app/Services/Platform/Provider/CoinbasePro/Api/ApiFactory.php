<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;
use App\Services\Platform\Resource\Order as OrderResource;

class ApiFactory extends ApiFactoryAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function candles(string $symbol, string $interval, string $start): Collection
    {
        return $this->handle(Candles::class, func_get_args());
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        return $this->handle(Check::class, func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function currencies(): Collection
    {
        return $this->handle(Currencies::class, func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function exchanges(): Collection
    {
        return $this->handle(Exchanges::class, func_get_args());
    }

    /**
     * @param string $symbol
     *
     * @return \App\Services\Platform\Resource\OrderBook
     */
    public function orderBook(string $symbol): OrderBookResource
    {
        return $this->handle(OrderBook::class, func_get_args());
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
        return $this->handle(OrderCreate::class, func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function ordersAll(): Collection
    {
        return $this->handle(OrdersAll::class, func_get_args());
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
        $this->handle(OrdersCancelAll::class, func_get_args());
    }

    /**
     * @param ?string $product = null
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersOpen(?string $product = null): Collection
    {
        return $this->handle(OrdersOpen::class, func_get_args());
    }

    /**
     * @param string $product
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersProduct(string $product): Collection
    {
        return $this->handle(OrdersProduct::class, func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function products(): Collection
    {
        return $this->handle(Products::class, func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function wallets(): Collection
    {
        return $this->handle(Wallets::class, func_get_args());
    }
}
