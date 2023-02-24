<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api\Traits;

use stdClass;
use App\Services\Platform\Resource\Order;

trait OrderResource
{
    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row): Order
    {
        $row = $this->resourceMap($row);

        $price = $this->resourcePrice($row);
        $amount = $this->resourceAmount($row);

        return new Order([
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
            'updatedAt' => $this->date($row->done_at),
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return \stdClass
     */
    protected function resourceMap(stdClass $row): stdClass
    {
        $row->price = floatval($row->price ?? 0);
        $row->size = floatval($row->size ?? 0);
        $row->executed_value = (float)$row->executed_value;
        $row->filled_size = (float)$row->filled_size;
        $row->fill_fees = (float)$row->fill_fees;
        $row->done_at ??= $row->created_at;

        return $row;
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourcePrice(stdClass $row): float
    {
        return round($row->price ?: ($row->executed_value / $row->filled_size), 12);
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourceAmount(stdClass $row): float
    {
        return $row->size ?: $row->filled_size;
    }
}
