<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Provider\CoinbasePro\Api\Traits\OrderResource as OrderResourceTrait;

class OrdersOpen extends ApiAbstract
{
    use OrderResourceTrait;

    /**
     * @var ?string
     */
    protected ?string $product;

    /**
     * @param ?string $product
     *
     * @return self
     */
    public function __construct(?string $product)
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
        return $this->requestAuth('GET', '/orders', array_filter([
            'status' => 'open',
            'product_id' => $this->product,
        ]));
    }
}
