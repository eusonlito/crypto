<?php declare(strict_types=1);

namespace App\Services\Trader\Simple;

class Buy extends TraderAbstract
{
    /**
     * @var array
     */
    protected array $config = [
        'trailing_delta_max' => 300,
        'trailing_delta_min' => 200,
    ];

    /**
     * @return array
     */
    public function calculate(): array
    {
        $stats = $this->getMarketStats();

        $range = $stats['range'];
        $volatility = $stats['volatility'];
        $trend = $stats['trend'];

        $supportLevel = $this->findMaxVolumeLevel($this->orderBook['bids']);
        $stopPrice = $this->adjustPriceNearSupport($supportLevel, $range);

        if ($trend > 0) {
            $stopPrice += $range * 0.01;
        }

        if ($stopPrice >= $this->priceCurrent) {
            $stopPrice = $this->priceCurrent * 0.99;
        }

        $trailingDelta = $this->calculateTrailingDelta($volatility, $trend);

        $minPercent = abs(($stopPrice / $this->priceCurrent) - 1) * 100;
        $maxPercent = $trailingDelta / 100.0;

        $price = $stopPrice + ($stopPrice * $maxPercent / 100);

        return [
            'reference' => $this->priceCurrent,
            'trailing_delta' => $trailingDelta,
            'price' => $price,
            'min_percent' => round($minPercent, 2),
            'max_percent' => round($maxPercent, 2),
        ];
    }

    /**
     * @param float $trend
     *
     * @return int
     */
    protected function calculateTrailingDeltaBips(float $trend): int
    {
        if ($trend === 0.0) {
            return 0;
        }

        if ($trend < 0) {
            return 50;
        }

        return -50;
    }
}
