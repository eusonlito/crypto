<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api\Traits;

use stdClass;
use App\Services\Platform\Resource\Order as OrderResourcePlatform;

trait OrderResource
{
    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row): OrderResourcePlatform
    {
        return new OrderResourcePlatform([
            'id' => $row->id,
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
