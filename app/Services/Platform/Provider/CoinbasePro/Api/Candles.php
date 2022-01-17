<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

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
     * @var int
     */
    protected int $interval;

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
     * @return int
     */
    protected function interval(string $interval): int
    {
        return match ($interval) {
            '1minute' => 60,
            '5minute' => 300,
            '15minute' => 900,
            '1hour' => 3600,
            '6hour' => 21600,
            '1day' => 86400,
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
        return $this->requestGuest('GET', sprintf('/products/%s/candles', $this->symbol), [
            'granularity' => $this->interval,
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
            'startAt' => date('Y-m-d H:i:s', $row[0]),
            'low' => (float)$row[1],
            'high' => (float)$row[2],
            'open' => (float)$row[3],
            'close' => (float)$row[4],
            'volume' => (float)$row[5],
            'endAt' => date('Y-m-d H:i:s', $row[0] + $this->interval - 1),
            'volumeQuote' => 0,
            'count' => 0,
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function prepare(Collection $collection): Collection
    {
        return $collection
            ->sortBy('startAt')
            ->filter(fn ($value) => $value->startAt >= $this->startTime)
            ->values();
    }
}
