<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

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
     * @param string $product
     *
     * @return self
     */
    public function __construct(string $product)
    {
        $this->product = $product;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->collection($this->query());
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/api/v3/allOrders', ['symbol' => $this->product]);
    }
}
