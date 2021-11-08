<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Provider\Kucoin\Api\Traits\OrderResource as OrderResourceTrait;

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
        return $this->collection($this->query()->data->items);
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/api/v1/orders', [
            'symbol' => $this->product,
        ]);
    }
}
