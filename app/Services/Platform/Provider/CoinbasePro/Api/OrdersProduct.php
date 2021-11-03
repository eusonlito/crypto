<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Order as OrderResource;

class OrdersProduct extends ApiAbstract
{
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
        return $this->requestAuth('GET', '/orders', [
            'status' => 'all',
            'product_id' => $this->product,
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row): OrderResource
    {
        $row->filled_size = (float)$row->filled_size;
        $row->executed_value = (float)$row->executed_value;

        $amount = $this->resourceAmount($row);
        $price = $this->resourcePrice($row);

        return new OrderResource([
            'id' => $row->id,
            'amount' => $amount,
            'price' => $price,
            'priceStop' => 0,
            'value' => ($price * $amount),
            'fee' => (float)$row->fill_fees,
            'product' => $row->product_id,
            'status' => $row->status,
            'type' => $row->type,
            'side' => $row->side,
            'filled' => ($row->status === 'done'),
            'createdAt' => $this->date($row->created_at),
            'updatedAt' => $this->date($row->done_at ?? $row->created_at),
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourcePrice(stdClass $row): float
    {
        return round(floatval($row->price ?? ($row->executed_value / $row->filled_size)), 12);
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourceAmount(stdClass $row): float
    {
        return floatval($row->size ?? $row->filled_size);
    }
}
