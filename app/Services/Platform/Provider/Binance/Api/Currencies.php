<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Currency as CurrencyResource;

class Currencies extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $endpoint = 'https://www.binance.com';

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
        return $this->requestGuest('GET', '/gateway-api/v2/public/asset/asset/get-all-asset');
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Currency
     */
    protected function resource(stdClass $row): ?CurrencyResource
    {
        return new CurrencyResource([
            'code' => $row->assetCode,
            'name' => $row->assetName,
            'symbol' => (string)$row->unit,
            'precision' => $row->assetDigit,
        ]);
    }
}
