<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Exchange as ExchangeResource;

class Exchanges extends ApiAbstract
{
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
        return $this->requestGuest('GET', '/api/v3/ticker/price');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Exchange
     */
    protected function resource(stdClass $row): ?ExchangeResource
    {
        if ((float)$row->price === 0.0) {
            return null;
        }

        return new ExchangeResource([
            'code' => $row->symbol,
            'price' => (float)$row->price,
            'createdAt' => date('Y-m-d H:i:s'),
        ]);
    }
}
