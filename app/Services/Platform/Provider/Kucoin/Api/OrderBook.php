<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;

class OrderBook extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $symbol;

    /**
     * @param string $symbol
     *
     * @return self
     */
    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return \App\Services\Platform\Resource\OrderBook
     */
    public function handle(): OrderBookResource
    {
        return $this->resource($this->query()->data);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', '/api/v3/market/orderbook/level2', [
            'symbol' => $this->symbol,
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\OrderBook
     */
    protected function resource(stdClass $row): OrderBookResource
    {
        return new OrderBookResource([
            'asks' => $this->map($row->asks),
            'bids' => $this->map($row->bids),
        ]);
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function map(array $lines): array
    {
        return array_map(static fn ($value) => [(float)$value[0], (float)$value[1]], $lines);
    }
}
