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
        if ($this->resourceValid($row) === false) {
            return null;
        }

        return new CurrencyResource([
            'code' => $row->assetCode,
            'name' => $row->assetName,
            'symbol' => (string)$row->unit,
            'precision' => $row->assetDigit,
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return bool
     */
    protected function resourceValid(stdClass $row): bool
    {
        return $row->trading
            && ($row->etf === false)
            && ($row->isLegalMoney || $row->tags)
            && $this->resourceValidInnovation($row);
    }

    /**
     * @param \stdClass $row
     *
     * @return bool
     */
    protected function resourceValidInnovation(stdClass $row): bool
    {
        return !in_array('innovation-zone', $row->tags)
            || in_array($row->assetCode, $this->config['currency_innovation_allowed']);
    }
}
