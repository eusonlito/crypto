<?php declare(strict_types=1);

namespace App\Services\Trader;

class Sell extends TraderAbstract
{
    /**
     * @var array
     */
    protected array $config = [
        'limitMin' => 5.0,                       // Minimum percentage for 'limit'
        'limitMax' => 10.0,                      // Maximum percentage for 'limit'
        'stopMin' => 1.5,                        // Minimum percentage for 'stop'
        'stopMax' => 2.5,                        // Maximum percentage for 'stop'
        'diffMin' => 2.5,                        // Minimum difference between 'limit' and 'stop'
        'stopVolatilityFactor' => 0.5,           // Factor to adjust 'stop' based on volatility
        'volatilityThreshold' => 1.5,            // Volatility threshold for adjustments (%)
        'volatilityFactor' => 0.4,               // Factor to adjust 'limit' based on volatility
        'volumeImbalance20Threshold' => 25.0,    // Volume imbalance threshold for 20 levels (%)
        'volumeImbalance10Threshold' => 20.0,    // Volume imbalance threshold for 10 levels (%)
        'limitIncrement' => 0.75,                // Increment for 'limit' based on volume imbalance (%)
        'stopFromLimit' => 3.0,                  // Factor to calculate 'stop' from 'limit'
        'volumeImbalanceWeight10' => 0.6,        // Weight for orderBooks[10]
        'volumeImbalanceWeight20' => 0.4,        // Weight for orderBooks[20]
        'rsiOverbought' => 65,                   // RSI overbought threshold
        'rsiOversold' => 35,                     // RSI oversold threshold
        'rsiLimitAdjust' => 0.5,                 // Adjustment of 'limit' based on RSI
        'smaAdjust' => 0.3,                      // Adjustment of 'limit' based on SMA
        'macdAdjust' => 0.3,                     // Adjustment of 'limit' based on MACD
        'bollingerAdjust' => 0.3,                // Adjustment of 'limit' based on Bollinger Bands
        'stochasticAdjust' => 0.3,               // Adjustment of 'limit' based on Stochastic Oscillator
        'highRiskVolatility' => 12.0,            // Volatility level considered high risk (%)
        'adxAdjust' => 0.3,                      // Adjustment of 'limit' based on ADX
        'aoAdjust' => 0.3,                       // Adjustment of 'limit' based on AO (Awesome Oscillator)
    ];

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitByPriceMinMax(float $limit): float
    {
        $limitPrice = $this->priceCurrent * (1 + $limit / 100);
        $maxPrice = max($this->pricesHigh);

        if ($limitPrice <= $maxPrice) {
            return $limit;
        }

        return (($maxPrice / $this->priceCurrent) - 1) * 100;
    }
}
