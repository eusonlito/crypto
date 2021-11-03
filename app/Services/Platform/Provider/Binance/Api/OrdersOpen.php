<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Order as OrderResource;

class OrdersOpen extends ApiAbstract
{
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
        return $this->requestAuth('GET', '/api/v3/openOrders', array_filter(['symbol' => $this->product]));
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row): OrderResource
    {
        $price = $this->resourcePrice($row);

        return new OrderResource([
            'id' => (string)$row->orderId,
            'amount' => (float)$row->origQty,
            'price' => $price,
            'priceStop' => (float)$row->stopPrice,
            'value' => ($price * (float)$row->origQty),
            'fee' => 0,
            'product' => $row->symbol,
            'status' => strtolower($row->status),
            'type' => strtolower($row->type),
            'side' => strtolower($row->side),
            'filled' => ($row->status === 'FILLED'),
            'createdAt' => date('Y-m-d H:i:s', intval($row->time / 1000)),
            'updatedAt' => date('Y-m-d H:i:s', intval($row->updateTime / 1000)),
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourcePrice(stdClass $row): float
    {
        $quantity = (float)$row->cummulativeQuoteQty;

        if ($quantity) {
            $price = $quantity / (float)$row->origQty;
        } else {
            $price = (float)$row->price;
        }

        return round($price, 12);
    }
}
