<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

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
     * @var string
     */
    protected string $startTime;

    /**
     * @param string $symbol
     * @param string $interval
     * @param string $start
     *
     * @return self
     */
    public function __construct(string $symbol, string $interval, string $start)
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
            '1minute' => '1m',
            '5minute' => '5m',
            '15minute' => '15m',
            '1hour' => '1h',
            '6hour' => '6h',
            '1day' => '1d',
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
        return $this->requestGuest('GET', '/api/v3/klines', [
            'symbol' => $this->symbol,
            'interval' => $this->interval,
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
            'high' => (float)$row[2],
            'low' => (float)$row[3],
            'close' => (float)$row[4],
            'volume' => (float)$row[5],
            'endAt' => $this->date($row[6]),
            'volumeQuote' => (float)$row[7],
            'count' => (int)$row[8],
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Illuminate\Support\Collection
     */
    protected function prepare(Collection $collection): Collection
    {
        return $collection
            ->sortBy('startAt')
            ->filter(fn ($value) => $value->startAt >= $this->startTime)
            ->values();
    }
}
