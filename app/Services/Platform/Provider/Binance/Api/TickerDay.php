<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Ticker as TickerResource;

class TickerDay extends ApiAbstract
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
        return $this->requestGuest('GET', '/api/v3/ticker/24hr');
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Ticker
     */
    protected function resource(stdClass $row): TickerResource
    {
        dd($row);

        return new TickerResource([
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
}
