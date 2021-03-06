<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Product as ProductResource;

class Products extends ApiAbstract
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
        return $this->requestGuest('GET', '/products');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Product
     */
    protected function resource(stdClass $row): ?ProductResource
    {
        if (($row->status !== 'online') || ($row->trading_disabled !== false)) {
            return null;
        }

        return new ProductResource([
            'code' => $row->id,
            'name' => $row->display_name,

            'precision' => 8,

            'priceMin' => 0,
            'priceMax' => 10000000,
            'priceDecimal' => strpos(str_replace('.', '', $row->quote_increment), '1'),

            'quantityMin' => 0,
            'quantityMax' => 10000000,
            'quantityDecimal' => strpos(str_replace('.', '', $row->base_increment), '1'),

            'currencyBase' => $row->base_currency,
            'currencyQuote' => $row->quote_currency,
        ]);
    }
}
