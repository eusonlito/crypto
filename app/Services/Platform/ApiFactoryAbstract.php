<?php declare(strict_types=1);

namespace App\Services\Platform;

use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Exchange as ExchangeResource;
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
     * @param string $symbol
     * @param string $interval
     * @param ?string $start = null
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function candles(string $symbol, string $interval, ?string $start = null): Collection;

    /**
     * @return bool
     */
    abstract public function check(): bool;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function currencies(): Collection;

    /**
     * @param string $symbol
     *
     * @return ?\App\Services\Platform\Resource\Exchange
     */
    abstract public function exchange(string $symbol): ?ExchangeResource;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function exchanges(): Collection;

    /**
     * @param string $symbol
     * @param int $limit = 1000
     *
     * @return \App\Services\Platform\Resource\OrderBook
     */
    abstract public function orderBook(string $symbol, int $limit = 1000): OrderBookResource;

    /**
     * @param string $product
     * @param string $side
     * @param string $type
     * @param array $data
     * @param ?string $reference = null
     *
     * @return \App\Services\Platform\Resource\Order
     */
    abstract public function orderCreate(string $product, string $side, string $type, array $data, ?string $reference = null): OrderResource;

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
     * @param bool $trades = false
     *
     * @return \Illuminate\Support\Collection
     */
    abstract public function ordersProduct(string $product, bool $trades = false): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function products(): Collection;

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function wallets(): Collection;
}
