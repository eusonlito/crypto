<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Currency as CurrencyResource;

class Currencies extends ApiAbstract
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
        return $this->requestGuest('GET', '/currencies');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Currency
     */
    protected function resource(stdClass $row): ?CurrencyResource
    {
        return new CurrencyResource([
            'code' => $row->id,
            'name' => $row->name,
            'symbol' => '',
            'precision' => substr_count($row->max_precision, '0'),
        ]);
    }
}
