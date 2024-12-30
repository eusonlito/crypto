<?php declare(strict_types=1);

namespace App\Services\Trader\Simple;

class Sell extends TraderAbstract
{
    /**
     * @var array
     */
    protected array $config = [
        'trailing_delta_max' => 250,
        'trailing_delta_min' => 150,
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

        $resistanceLevel = $this->findMaxVolumeLevel($this->orderBook['asks']);
        $stopPrice = $this->adjustPriceNearResistance($resistanceLevel, $range);

        if ($trend < 0) {
            $stopPrice -= $range * 0.01;
        }

        if ($stopPrice <= $this->priceCurrent) {
            $stopPrice = $this->priceCurrent * 1.01;
        }

        $trailingDelta = $this->calculateTrailingDelta($volatility, $trend);

        $maxPercent = abs(($stopPrice / $this->priceCurrent) - 1) * 100;
        $minPercent = $trailingDelta / 100.0;

        $price = $stopPrice - ($stopPrice * $minPercent / 100);

        return [
            'reference' => $stopPrice,
            'trailing_delta' => $trailingDelta,
            'price' => $price,
            'max_percent' => round($maxPercent, 2),
            'min_percent' => round($minPercent, 2),
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

        if ($trend > 0) {
            return 50;
        }

        return -50;
    }
}
