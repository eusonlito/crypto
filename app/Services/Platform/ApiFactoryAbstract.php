<?php declare(strict_types=1);

namespace App\Services\Platform;

use Illuminate\Support\Collection;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;
use App\Services\Platform\Resource\Order as OrderResource;

abstract class ApiFactoryAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @param array $config
     *
     * @return self
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $class
     * @param array $args
     *
     * @return mixed
     */
    public function handle(string $class, array $args)
    {
        return (new $class(...$args))->config($this->config)->handle();
    }

    /**
     * @return bool
     */
    abstract public function check(): bool;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function currencies(): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function exchanges(): Collection;

    /**
     * @param string $symbol
     *
     * @return \App\Services\Platform\Resource\OrderBook
     */
    abstract public function orderBook(string $symbol): OrderBookResource;

    /**
     * @param string $product
     * @param string $side
     * @param string $type
     * @param array $data
     *
     * @return \App\Services\Platform\Resource\Order
     */
    abstract public function orderCreate(string $product, string $side, string $type, array $data): OrderResource;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function ordersAll(): Collection;

    /**
     * @return bool
     */
    abstract public function ordersAllAvailable(): bool;

    /**
     * @param string $product
     *
     * @return void
     */
    abstract public function ordersCancelAll(string $product): void;

    /**
     * @param ?string $product = null
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function ordersOpen(?string $product = null): Collection;

    /**
     * @param string $product
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function ordersProduct(string $product): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function products(): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function tickerDay(): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function wallets(): Collection;
}
