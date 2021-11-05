<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Product as ProductResource;

class Products extends ApiAbstract
{
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
        return $this->collection($this->query()->data);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', '/api/v1/symbols');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Product
     */
    protected function resource(stdClass $row): ?ProductResource
    {
        if ($row->enableTrading !== true) {
            return null;
        }

        return new ProductResource([
            'code' => $row->symbol,
            'name' => ($row->baseCurrency.'/'.$row->quoteCurrency),

            'precision' => 8,

            'priceMin' => (float)$row->quoteMinSize,
            'priceMax' => (float)$row->quoteMaxSize,
            'priceDecimal' => strpos(str_replace('.', '', $row->quoteIncrement), '1'),

            'quantityMin' => (float)$row->baseMinSize,
            'quantityMax' => (float)$row->baseMaxSize,
            'quantityDecimal' => strpos(str_replace('.', '', $row->baseIncrement), '1'),

            'currencyBase' => $row->baseCurrency,
            'currencyQuote' => $row->quoteCurrency,
        ]);
    }
}
