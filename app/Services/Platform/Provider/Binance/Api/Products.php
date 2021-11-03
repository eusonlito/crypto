<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Product as ProductResource;

class Products extends ApiAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $tiker24h;

    /**
     * @var bool
     */
    protected bool $filter;

    /**
     * @param bool $filter
     *
     * @return self
     */
    public function __construct(bool $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        $this->tiker24h();

        return $this->collection($this->query()->symbols);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', '/api/v3/exchangeInfo');
    }

    /**
     * @return void
     */
    protected function tiker24h(): void
    {
        $this->tiker24h = collect(array_filter($this->tiker24hRequest(), static fn ($value) => $value->count > 1000))->keyBy('symbol');
    }

    /**
     * @return array
     */
    protected function tiker24hRequest(): array
    {
        return $this->requestGuest('GET', '/api/v3/ticker/24hr');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Product
     */
    protected function resource(stdClass $row): ?ProductResource
    {
        if ($this->resourceValid($row) === false) {
            return null;
        }

        $lotSize = $this->resourceFilter($row, 'LOT_SIZE');
        $priceFilter = $this->resourceFilter($row, 'PRICE_FILTER');

        return new ProductResource([
            'code' => $row->symbol,
            'name' => ($row->baseAsset.'/'.$row->quoteAsset),

            'precision' => 8,

            'priceMin' => (float)$priceFilter->minPrice,
            'priceMax' => (float)$priceFilter->maxPrice,
            'priceDecimal' => strpos(str_replace('.', '', $priceFilter->tickSize), '1'),

            'quantityMin' => (float)$lotSize->minQty,
            'quantityMax' => (float)$lotSize->maxQty,
            'quantityDecimal' => strpos(str_replace('.', '', $lotSize->stepSize), '1'),

            'currencyBase' => $row->baseAsset,
            'currencyQuote' => $row->quoteAsset,
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return bool
     */
    protected function resourceValid(stdClass $row): bool
    {
        if ($row->status !== 'TRADING') {
            return false;
        }

        if ($this->filter && ($this->tiker24h->has($row->symbol) === false)) {
            return false;
        }

        return true;
    }

    /**
     * @param \stdClass $row
     * @param string $type
     *
     * @return stdClass
     */
    protected function resourceFilter(stdClass $row, string $type): stdClass
    {
        return current(array_filter($row->filters, static fn ($value) => $value->filterType === $type));
    }
}
