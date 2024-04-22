<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api\Traits;

use stdClass;
use App\Services\Platform\Resource\Order;

trait OrderResource
{
    /**
     * @param \stdClass $row
     * @param int $key = 0
     * @param array $trades = []
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row, int $key = 0, array $trades = []): Order
    {
        return new Order([
            'id' => $row->id,
            'reference' => '',
            'amount' => (float)$row->size,
            'price' => (float)$row->price,
            'priceStop' => (float)$row->stopPrice,
            'value' => (float)$row->funds,
            'fee' => (float)$row->fee,
            'product' => $row->symbol,
            'status' => $this->status($row),
            'type' => $row->type,
            'side' => $row->side,
            'filled' => $this->filled($row),
            'trades' => [],
            'createdAt' => $this->date($row->createdAt),
            'updatedAt' => $this->date($row->createdAt),
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return string
     */
    protected function status(stdClass $row): string
    {
        if ($row->cancelExist) {
            return 'canceled';
        }

        if ($row->isActive) {
            return 'filled';
        }

        return 'pending';
    }

    /**
     * @param \stdClass $row
     *
     * @return bool
     */
    protected function filled(stdClass $row): bool
    {
        return ($row->isActive === false)
            && empty($row->cancelExist);
    }
}
