<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Candle as CandleResource;
use App\Services\Platform\Exception\InvalidIntervalException;

class Candles extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $symbol;

    /**
     * @var string
     */
    protected string $interval;

    /**
     * @var ?string
     */
    protected ?string $startTime;

    /**
     * @param string $symbol
     * @param string $interval
     * @param ?string $start = null
     *
     * @return self
     */
    public function __construct(string $symbol, string $interval, ?string $start = null)
    {
        $this->symbol = $symbol;
        $this->interval = $this->interval($interval);
        $this->startTime = $start;
    }

    /**
     * @param string $interval
     *
     * @return string
     */
    protected function interval(string $interval): string
    {
        return match ($interval) {
            '1minute' => '1min',
            '5minute' => '5min',
            '15minute' => '15min',
            '1hour' => '1hour',
            '6hour' => '6hour',
            '1day' => '1day',
            default => throw new InvalidIntervalException($interval),
        };
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->prepare($this->collection($this->query()));
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestGuest('GET', '/api/v1/market/candles', [
            'symbol' => $this->symbol,
            'type' => $this->interval,
        ]);
    }

    /**
     * @param array $row
     *
     * @return \App\Services\Platform\Resource\Candle
     */
    protected function resource(array $row): CandleResource
    {
        return new CandleResource([
            'startAt' => $this->date($row[0]),
            'open' => (float)$row[1],
            'close' => (float)$row[2],
            'high' => (float)$row[3],
            'low' => (float)$row[4],
            'volume' => (float)$row[5],
            'endAt' => '',
            'volumeQuote' => (float)$row[6],
            'count' => 0,
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Illuminate\Support\Collection
     */
    protected function prepare(Collection $collection): Collection
    {
        $collection = $collection->sortBy('startAt');

        if ($this->startTime) {
            $collection = $collection->filter(fn ($value) => $value->startAt >= $this->startTime);
        }

        return $collection->values();
    }
}
