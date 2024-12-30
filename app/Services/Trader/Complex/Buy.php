<?php declare(strict_types=1);

namespace App\Services\Trader\Complex;

class Buy extends TraderAbstract
{
    /**
     * @var array
     */
    protected array $config = [
        'limitMin' => 7.0,                       // Minimum percentage for 'limit'
        'limitMax' => 15.0,                      // Maximum percentage for 'limit'
        'stopMin' => 3.0,                        // Minimum percentage for 'stop'
        'stopMax' => 4.5,                        // Maximum percentage for 'stop'
        'diffMin' => 3.0,                        // Minimum difference between 'limit' and 'stop'
        'stopVolatilityFactor' => 0.5,           // Factor to adjust 'stop' based on volatility
        'volatilityThreshold' => 1.5,            // Volatility threshold for adjustments (%)
        'volatilityFactor' => 0.6,               // Factor to adjust 'limit' based on volatility
        'volumeImbalance20Threshold' => 20.0,    // Volume imbalance threshold for 20 levels (%)
        'volumeImbalance10Threshold' => 15.0,    // Volume imbalance threshold for 10 levels (%)
        'limitIncrement' => 1.0,                 // Increment for 'limit' based on volume imbalance (%)
        'stopFromLimit' => 3.0,                  // Factor to calculate 'stop' from 'limit'
        'volumeImbalanceWeight10' => 0.6,        // Weight for orderBooks[10]
        'volumeImbalanceWeight20' => 0.4,        // Weight for orderBooks[20]
        'rsiOverbought' => 70,                   // RSI overbought threshold
        'rsiOversold' => 30,                     // RSI oversold threshold
        'rsiLimitAdjust' => 1.0,                 // Adjustment of 'limit' based on RSI
        'smaAdjust' => 0.5,                      // Adjustment of 'limit' based on SMA
        'macdAdjust' => 0.5,                     // Adjustment of 'limit' based on MACD
        'bollingerAdjust' => 0.5,                // Adjustment of 'limit' based on Bollinger Bands
        'stochasticAdjust' => 0.5,               // Adjustment of 'limit' based on Stochastic Oscillator
        'highRiskVolatility' => 10.0,            // Volatility level considered high risk (%)
        'adxAdjust' => 0.5,                      // Adjustment of 'limit' based on ADX
        'aoAdjust' => 0.5,                       // Adjustment of 'limit' based on AO (Awesome Oscillator)
    ];

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitByPriceMinMax(float $limit): float
    {
        $limitPrice = $this->priceCurrent * (1 - $limit / 100);
        $minPrice = min($this->pricesLow);

        if ($limitPrice >= $minPrice) {
            return $limit;
        }

        return (1 - ($minPrice / $this->priceCurrent)) * 100;
    }
}
