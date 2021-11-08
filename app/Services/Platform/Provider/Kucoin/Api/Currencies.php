<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

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
        return $this->collection($this->query()->data);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', '/api/v1/currencies');
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Currency
     */
    protected function resource(stdClass $row): CurrencyResource
    {
        return new CurrencyResource([
            'code' => $row->currency,
            'name' => $row->fullName,
            'symbol' => $row->currency,
            'precision' => (int)$row->precision,
        ]);
    }
}
