<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api\Traits;

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

        return new Order([
            'id' => $row->orderId,
            'reference' => $row->reference,
            'amount' => $row->origQty,
            'price' => $this->resourcePrice($row),
            'priceStop' => $row->stopPrice,
            'value' => $this->resourceValue($row),
            'fee' => $this->resourceFee($row),
            'product' => $row->symbol,
            'status' => $row->status,
            'type' => $row->type,
            'side' => $row->side,
            'filled' => ($row->status === 'filled'),
            'createdAt' => $this->date($row->time),
            'updatedAt' => $this->date($row->updateTime),
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return \stdClass
     */
    protected function resourceMap(stdClass $row): stdClass
    {
        $row->orderId = strval($row->orderId);
        $row->reference = strval($row->clientOrderId);
        $row->origQty = floatval($row->origQty);
        $row->price = floatval($row->price);
        $row->executedQty = floatval($row->executedQty);
        $row->cummulativeQuoteQty = floatval($row->cummulativeQuoteQty);
        $row->stopPrice = floatval($row->stopPrice ?? null);
        $row->status = strtolower($row->status);
        $row->type = strtolower($row->type);
        $row->side = strtolower($row->side);
        $row->time ??= $row->transactTime;
        $row->updateTime ??= $row->transactTime;

        return $row;
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourcePrice(stdClass $row): float
    {
        if ($row->executedQty) {
            return $row->cummulativeQuoteQty / $row->executedQty;
        }

        return round($row->price, 12);
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourceValue(stdClass $row): float
    {
        if (empty($row->cummulativeQuoteQty)) {
            return $row->price * $row->origQty;
        }

        return $row->cummulativeQuoteQty;
    }

    /**
     * @param \stdClass $row
     *
     * @return float
     */
    protected function resourceFee(stdClass $row): float
    {
        return round(($this->resourcePrice($row) * $row->executedQty) - $row->cummulativeQuoteQty, 12);
    }
}
