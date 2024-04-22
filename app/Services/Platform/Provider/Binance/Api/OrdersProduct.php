<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use ArrayObject;
use Illuminate\Support\Collection;
use App\Services\Platform\Provider\Binance\Api\Traits\OrderResource as OrderResourceTrait;

class OrdersProduct extends ApiAbstract
{
    use OrderResourceTrait;

    /**
     * @var string
     */
    protected string $product;

    /**
     * @var bool
     */
    protected bool $trades;

    /**
     * @param string $product
     * @param bool $trades = false
     *
     * @return self
     */
    public function __construct(string $product, bool $trades = false)
    {
        $this->product = $product;
        $this->trades = $trades;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->collection($this->query(), $this->trades());
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/api/v3/allOrders', ['symbol' => $this->product]);
    }

    /**
     * @return array
     */
    protected function trades(): array
    {
        if ($this->trades === false) {
            return [];
        }

        return array_reduce($this->tradesQuery(), static function ($carry, $item) {
            return $carry[$item->orderId] = $item;
        }, new ArrayObject())->getArrayCopy();
    }

    /**
     * @return array
     */
    protected function tradesQuery(): array
    {
        return $this->requestAuth('GET', '/api/v3/myTrades', ['symbol' => $this->product]);
    }
}
