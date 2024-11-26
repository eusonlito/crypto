<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use stdClass;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;

class OrderBook extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $symbol;

    /**
     * @var int
     */
    protected int $limit;

    /**
     * @param string $symbol
     * @param int $limit = 1000
     *
     * @return self
     */
    public function __construct(string $symbol, int $limit = 1000)
    {
        $this->symbol = $symbol;
        $this->limit = $limit;
    }

    /**
     * @return \App\Services\Platform\Resource\OrderBook
     */
    public function handle(): OrderBookResource
    {
        return $this->resource($this->query());
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', sprintf('/products/%s/book', $this->symbol), [
            'level' => 3,
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
