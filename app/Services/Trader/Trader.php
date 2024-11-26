<?php declare(strict_types=1);

namespace App\Services\Trader;

use Exception;
use App\Services\Platform\ApiFactoryAbstract;

class Trader
{
    /**
     * @var array
     */
    protected array $configBuy = [
        'limitMin' => 7.0,                       // Minimum percentage for 'limit'
        'limitMax' => 15.0,                      // Maximum percentage for 'limit'
        'stopMin' => 3.0,                        // Minimum percentage for 'stop'
        'stopMax' => 4.5,                        // Maximum percentage for 'stop'
        'stopVolatilityFactor' => 0.5,           // Factor to adjust 'stop' based on volatility
        'diffMin' => 3.0,                        // Minimum difference between 'limit' and 'stop'
        'volatilityThreshold' => 3.0,            // Volatility threshold for adjustments (%)
        'volatilityMax' => 8.0,                  // Maximum volatility to allow purchase (%)
        'volatilityFactor' => 1.0,               // Factor to adjust 'limit' based on volatility
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
    ];

    /**
     * @var array
     */
    protected array $configSell = [
        'limitMin' => 5.0,                       // Minimum percentage for 'limit'
        'limitMax' => 12.0,                      // Maximum percentage for 'limit'
        'stopMin' => 1.5,                        // Minimum percentage for 'stop'
        'stopMax' => 3.0,                        // Maximum percentage for 'stop'
        'stopVolatilityFactor' => 0.5,           // Factor to adjust 'stop' based on volatility
        'diffMin' => 3.0,                        // Minimum difference between 'limit' and 'stop'
        'volatilityThreshold' => 3.0,            // Volatility threshold for adjustments (%)
        'volatilityMax' => 10.0,                 // Maximum volatility to allow sale (%)
        'volatilityFactor' => 0.8,               // Factor to adjust 'limit' based on volatility
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
    ];

    /**
     * @var array
     */
    protected array $config;

    /**
     * @var array
     */
    protected array $candles = [];

    /**
     * @var array
     */
    protected array $orderBooks = [];

    /**
     * @var array
     */
    protected array $prices = [];

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $symbol
     * @param string $type
     * @param ApiFactoryAbstract $api
     *
     * @return void
     */
    public function __construct(
        protected string $symbol,
        protected string $type,
        protected ApiFactoryAbstract $api
    ) {
        $this->setConfigType($type);
        $this->candles();
        $this->orderBooks();
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function setConfigType(string $type): void
    {
        $this->config = match (strtolower($type)) {
            'buy' => $this->configBuy,
            'sell' => $this->configSell,
            default => throw new Exception(sprintf('Invalid configuration type: %s', $type)),
        };
    }

    /**
     * @param array $config
     *
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return void
     */
    protected function candles(): void
    {
        $this->candle('5minute');
        $this->candle('15minute');
    }

    /**
     * @param string $interval
     *
     * @return void
     */
    protected function candle(string $interval): void
    {
        $this->candles[$interval] = $this->api->candles($this->symbol, $interval, $this->candleDate())->all();
        $this->prices[$interval] = array_column($this->candles[$interval], 'close');
    }

    /**
     * @return string
     */
    protected function candleDate(): string
    {
        return date('Y-m-d H:i:s', strtotime('-3 days'));
    }

    /**
     * @return void
     */
    protected function orderBooks(): void
    {
        $this->orderBook(10);
        $this->orderBook(20);
    }

    /**
     * @param int $limit
     *
     * @return void
     */
    protected function orderBook(int $limit): void
    {
        $this->orderBooks[$limit] = $this->api->orderBook($this->symbol, $limit);
    }

    /**
     * @return float
     */
    protected function currentPrice(): float
    {
        return end($this->prices['5minute']);
    }

    /**
     * @return array
     */
    public function limitStop(): array
    {
        // Obtain indicators
        $indicators = $this->indicators();

        // Extract necessary values
        $rsi = $indicators['rsi'];
        $sma50 = $indicators['sma_50'];
        $sma200 = $indicators['sma_200'];
        $macd = $indicators['macd'];
        $macd_signal = $indicators['macd_signal'];
        $volatilityAverage = $indicators['volatility_average'];

        // Initialize 'limit' with the configured minimum
        $limit = $this->config['limitMin'];

        // Adjust 'limit' based on volatility
        $limit += $this->adjustLimitByVolatility($volatilityAverage);

        // Adjust 'limit' based on volume imbalance
        $limit += $this->adjustLimitByVolumeImbalance();

        // Adjust 'limit' based on RSI
        $limit += $this->adjustLimitByRSI($rsi);

        // Adjust 'limit' based on SMA
        $limit += $this->adjustLimitBySMA($sma50, $sma200);

        // Adjust 'limit' based on MACD
        $limit += $this->adjustLimitByMACD($macd, $macd_signal);

        // Ensure 'limit' is not below the minimum
        $limit = $this->adjustLimitMin($limit);

        // Calculate 'stop'
        $stop = $this->adjustStop($limit);

        // Adjust 'stop' based on volatility
        $stop += $this->adjustStopByVolatility($volatilityAverage);

        // Ensure 'stop' meets the minimum and maximum configured
        $stop = $this->adjustStopMinMax($stop);

        // Ensure the difference between 'limit' and 'stop' meets the minimum configured
        [$limit, $stop] = $this->adjustLimitStopDiff($limit, $stop);

        // Round the results to two decimal places
        return [
            'limit' => round($limit, 2),
            'stop' => round($stop, 2),
        ];
    }

    /**
     * @param float $volatilityAverage
     *
     * @return float
     */
    protected function adjustLimitByVolatility(float $volatilityAverage): float
    {
        if ($volatilityAverage < $this->config['volatilityThreshold']) {
            return 0;
        }

        $value = ($volatilityAverage - $this->config['volatilityThreshold']) * $this->config['volatilityFactor'];

        if ($volatilityAverage > $this->config['volatilityMax']) {
            $value *= 2;
        }

        return $value;
    }

    /**
     * @return float
     */
    protected function adjustLimitByVolumeImbalance(): float
    {
        $volumeImbalance10 = $this->calculateVolumeImbalance(10);
        $volumeImbalance20 = $this->calculateVolumeImbalance(20);

        // Weighted combined volume imbalance
        $combinedVolumeImbalance = ($volumeImbalance10 * $this->config['volumeImbalanceWeight10'])
            + ($volumeImbalance20 * $this->config['volumeImbalanceWeight20']);

        // Adjust 'limit' based on combined volume imbalance
        if (abs($combinedVolumeImbalance) > $this->config['volumeImbalance20Threshold']) {
            return $this->config['limitIncrement'] * ($combinedVolumeImbalance / 100);
        }

        return 0.0;
    }

    /**
     * @param float $rsi
     *
     * @return float
     */
    protected function adjustLimitByRSI(float $rsi): float
    {
        // Overbought condition
        if ($rsi > $this->config['rsiOverbought']) {
            // Reduce 'limit' to protect against a drop
            return -$this->config['rsiLimitAdjust'];
        }

        // Oversold condition
        if ($rsi < $this->config['rsiOversold']) {
            // Increase 'limit' anticipating a rebound
            return $this->config['rsiLimitAdjust'];
        }

        return 0.0;
    }

    /**
     * @param float $sma50
     * @param float $sma200
     *
     * @return float
     */
    protected function adjustLimitBySMA(float $sma50, float $sma200): float
    {
        $currentPrice = $this->currentPrice();

        // Check for Golden Cross (bullish signal)
        if ($sma50 > $sma200 && $currentPrice > $sma50) {
            // Significantly increase 'limit'
            return $this->config['smaAdjust'] * 2;
        }

        // Check for Death Cross (bearish signal)
        if ($sma50 < $sma200 && $currentPrice < $sma50) {
            // Significantly reduce 'limit'
            return -($this->config['smaAdjust'] * 2);
        }

        // Bullish trend
        if ($currentPrice > $sma50) {
            // Slightly increase 'limit'
            return $this->config['smaAdjust'];
        }

        // Bearish trend
        if ($currentPrice < $sma50) {
            // Slightly reduce 'limit'
            return -$this->config['smaAdjust'];
        }

        return 0.0;
    }

    /**
     * @param float $macd
     * @param float $macd_signal
     *
     * @return float
     */
    protected function adjustLimitByMACD(float $macd, float $macd_signal): float
    {
        // Bullish crossover
        if ($macd > $macd_signal) {
            // Increase 'limit' anticipating greater bullish momentum
            return $this->config['macdAdjust'];
        }

        // Bearish crossover
        if ($macd < $macd_signal) {
            // Reduce 'limit' anticipating a drop
            return -$this->config['macdAdjust'];
        }

        return 0.0;
    }

    /**
     * @param float $limit
     * @param float $stop
     *
     * @return array
     */
    protected function adjustLimitStopDiff(float $limit, float $stop): array
    {
        if (($limit - $stop) >= $this->config['diffMin']) {
            return [$limit, $stop];
        }

        $stop = $limit - $this->config['diffMin'];

        if ($stop >= $this->config['stopMin']) {
            return [$limit, $stop];
        }

        $stop = $this->config['stopMin'];
        $limit = $stop + $this->config['diffMin'];

        return [$limit, $stop];
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustStop(float $limit): float
    {
        return $limit / $this->config['stopFromLimit'];
    }

    /**
     * @param float $volatility
     *
     * @return float
     */
    protected function adjustStopByVolatility(float $volatility): float
    {
        return $volatility * $this->config['stopVolatilityFactor'];
    }

    /**
     * @param float $stop
     *
     * @return float
     */
    protected function adjustStopMinMax(float $stop): float
    {
        if ($stop < $this->config['stopMin']) {
            return $this->config['stopMin'];
        }

        if ($stop > $this->config['stopMax']) {
            return $this->config['stopMax'];
        }

        return $stop;
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitMin(float $limit): float
    {
        if ($limit < $this->config['limitMin']) {
            return $this->config['limitMin'];
        }

        return $limit;
    }

    /**
     * @param array $prices
     * @param int $shortPeriod
     * @param int $longPeriod
     * @param int $signalPeriod
     *
     * @return array
     */
    public function macd(array $prices, int $shortPeriod = 12, int $longPeriod = 26, int $signalPeriod = 9): array
    {
        $emaShort = $this->ema($prices, $shortPeriod);
        $emaLong = $this->ema($prices, $longPeriod);

        $alignedLength = min(count($emaShort), count($emaLong));
        $emaShort = array_slice($emaShort, -$alignedLength);
        $emaLong = array_slice($emaLong, -$alignedLength);

        $macdLine = array_map(fn ($short, $long) => $short - $long, $emaShort, $emaLong);

        $signalLine = $this->ema($macdLine, $signalPeriod);

        if (empty($signalLine)) {
            return [
                'macd' => [],
                'signal' => [],
                'histogram' => [],
            ];
        }

        $alignedLength = min(count($macdLine), count($signalLine));
        $macdLine = array_slice($macdLine, -$alignedLength);
        $signalLine = array_slice($signalLine, -$alignedLength);

        $histogram = array_map(fn ($macd, $signal) => $macd - $signal, $macdLine, $signalLine);

        return [
            'macd' => $macdLine,
            'signal' => $signalLine,
            'histogram' => $histogram,
        ];
    }

    /**
     * @param array $prices
     * @param int $period
     *
     * @return array
     */
    public function ema(array $prices, int $period): array
    {
        $ema = [];
        $count = count($prices);

        if ($count < $period) {
            return $ema;
        }

        $k = 2 / ($period + 1);
        $sma = array_sum(array_slice($prices, 0, $period)) / $period;
        $ema[] = $sma;
        $previous = $sma;

        for ($i = $period; $i < $count; $i++) {
            $current = ($prices[$i] - $previous) * $k + $previous;
            $ema[] = $current;
            $previous = $current;
        }

        return $ema;
    }

    /**
     * @param array $prices
     * @param int $period
     *
     * @return array
     */
    public function rsi(array $prices, int $period = 14): array
    {
        $rsi = [];
        $count = count($prices);

        if ($count <= $period) {
            return $rsi;
        }

        $gains = $losses = [];

        for ($i = 1; $i <= $period; $i++) {
            $delta = $prices[$i] - $prices[$i - 1];
            $gains[] = $delta > 0 ? $delta : 0;
            $losses[] = $delta < 0 ? abs($delta) : 0;
        }

        $avgGain = array_sum($gains) / $period;
        $avgLoss = array_sum($losses) / $period;
        $rsi[] = empty($avgLoss) ? 100 : 100 - (100 / (1 + ($avgGain / $avgLoss)));

        for ($i = $period + 1; $i < $count; $i++) {
            $delta = $prices[$i] - $prices[$i - 1];
            $gain = $delta > 0 ? $delta : 0;
            $loss = $delta < 0 ? abs($delta) : 0;

            $avgGain = (($avgGain * ($period - 1)) + $gain) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $loss) / $period;

            $rsi[] = empty($avgLoss) ? 100 : 100 - (100 / (1 + ($avgGain / $avgLoss)));
        }

        return $rsi;
    }

    /**
     * @param array $prices
     * @param int $period
     *
     * @return array
     */
    public function sma(array $prices, int $period): array
    {
        $sma = [];
        $count = count($prices);

        if ($count < $period) {
            return $sma;
        }

        $sum = array_sum(array_slice($prices, 0, $period));
        $sma[] = $sum / $period;

        for ($i = $period; $i < $count; $i++) {
            $sum = $sum - $prices[$i - $period] + $prices[$i];
            $sma[] = $sum / $period;
        }

        return $sma;
    }

    /**
     * @return array
     */
    public function indicators(): array
    {
        $prices5m = $this->prices['5minute'];
        $prices15m = $this->prices['15minute'];

        $indicators = [];

        // Calculate RSI
        $rsi = $this->rsi($prices5m);
        $indicators['rsi'] = $this->round(end($rsi) ?? 0, 4);

        // Calculate SMA
        $sma50 = $this->sma($prices5m, 50);
        $sma200 = $this->sma($prices5m, 200);
        $decimals = $this->decimals($prices5m);

        $indicators['sma_50'] = $this->round(end($sma50) ?? 0, $decimals);
        $indicators['sma_200'] = $this->round(end($sma200) ?? 0, $decimals);

        // Calculate MACD
        $macd = $this->macd($prices5m);

        $indicators['macd'] = $this->round(end($macd['macd']) ?? 0, 4);
        $indicators['macd_signal'] = $this->round(end($macd['signal']) ?? 0, 4);
        $indicators['macd_hist'] = $this->round(end($macd['histogram']) ?? 0, 4);

        // Calculate Volatility
        $volatility5m = $this->volatility($prices5m, 5);
        $volatility15m = $this->volatility($prices15m, 15);
        $volatilityAverage = ($volatility5m + $volatility15m) / 2;

        $indicators['volatility_5m'] = $this->round($volatility5m, 4);
        $indicators['volatility_15m'] = $this->round($volatility15m, 4);
        $indicators['volatility_average'] = $this->round($volatilityAverage, 4);

        return $indicators;
    }

    /**
     * @param array $prices
     *
     * @return int
     */
    public function decimals(array $prices): int
    {
        $decimals = [];

        foreach ($prices as $price) {
            $parts = explode('.', (string)$price);
            $decimals[] = isset($parts[1]) ? strlen(rtrim($parts[1], '0')) : 0;
        }

        return $decimals ? intval(array_sum($decimals) / count($decimals)) : 0;
    }

    /**
     * @param int|float|bool|null $value
     * @param int $decimals
     *
     * @return int|float|bool|null
     */
    public function round(int|float|bool|null $value, int $decimals): int|float|bool|null
    {
        return is_numeric($value) ? round($value, $decimals) : $value;
    }

    /**
     * @param int $limit
     *
     * @return float
     */
    protected function calculateVolumeImbalance(int $limit): float
    {
        $orderBook = $this->orderBooks[$limit] ?? [];

        if (empty($orderBook)) {
            return 0.0;
        }

        $bids = array_sum(array_column($orderBook->bids, 1));
        $asks = array_sum(array_column($orderBook->asks, 1));

        if (($bids + $asks) <= 0) {
            return 0.0;
        }

        return (($bids - $asks) / ($bids + $asks)) * 100;
    }

    /**
     * @param array $prices
     *
     * @return float
     */
    protected function volatility(array $prices, int $minutes): float
    {
        $count = count($prices);

        if ($count < 2) {
            return 0.0;
        }

        $returns = [];

        for ($i = 1; $i < $count; $i++) {
            $return = log($prices[$i] / $prices[$i - 1]);
            $returns[] = $return;
        }

        $count = count($returns);
        $meanReturn = array_sum($returns) / $count;

        $variance = array_sum(array_map(static fn ($x) => pow($x - $meanReturn, 2), $returns));
        $variance = $variance / ($count - 1);

        $minutesPerDay = 6.5 * 60;
        $periodsPerDay = $minutesPerDay / $minutes;

        return sqrt($variance) * sqrt($periodsPerDay) * 100;
    }
}
