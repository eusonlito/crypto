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
     * @var array
     */
    protected array $configSell = [
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
     * @var array
     */
    protected array $config;

    /**
     * @var array
     */
    protected array $orderBooks = [];

    /**
     * @var array
     */
    protected array $prices = [];

    /**
     * @var array
     */
    protected array $pricesLow = [];

    /**
     * @var array
     */
    protected array $pricesHigh = [];

    /**
     * @var float
     */
    protected float $priceCurrent;

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
     * @param \App\Services\Platform\ApiFactoryAbstract $api
     *
     * @return void
     */
    public function __construct(
        protected string $symbol,
        protected string $type,
        protected ApiFactoryAbstract $api
    ) {
        $this->setConfigType($type);
        $this->prices();
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
    protected function prices(): void
    {
        $this->price('5minute');
        $this->price('15minute');
    }

    /**
     * @param string $interval
     *
     * @return void
     */
    protected function price(string $interval): void
    {
        $candles = $this->api->candles($this->symbol, $interval, $this->priceDate())->all();

        $this->prices[$interval] = array_column($candles, 'close');

        if ($interval !== '5minute') {
            return;
        }

        $this->priceCurrent = end($this->prices['5minute']);

        $this->pricesHigh = array_column($candles, 'high');
        $this->pricesLow = array_column($candles, 'low');
    }

    /**
     * @return string
     */
    protected function priceDate(): string
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
     * @return array
     */
    public function limitStop(): array
    {
        $indicators = $this->indicators();

        $rsi = $indicators['rsi'];
        $sma50 = $indicators['sma_50'];
        $sma200 = $indicators['sma_200'];
        $macd = $indicators['macd'];
        $macd_signal = $indicators['macd_signal'];
        $volatilityAverage = $indicators['volatility_average'];
        $atr = $indicators['atr'];
        $stochastic = $indicators['stochastic'];
        $adx = $indicators['adx'];
        $ao = $indicators['ao'];
        $latestBand = end($indicators['bollinger_bands']);

        $limit = $this->config['limitMin'];

        $limit += $this->adjustLimitByVolatility($volatilityAverage);
        $limit += $this->adjustLimitByVolumeImbalance();
        $limit += $this->adjustLimitByRSI($rsi);
        $limit += $this->adjustLimitBySMA($sma50, $sma200);
        $limit += $this->adjustLimitByMACD($macd, $macd_signal);
        $limit += $this->adjustLimitByBollingerBands($latestBand);
        $limit += $this->adjustLimitByStochastic($stochastic);
        $limit += $this->adjustLimitByADX($adx);
        $limit += $this->adjustLimitByAO($ao);

        $limit = $this->adjustLimitByPriceMinMax($limit);
        $limit = $this->adjustLimitMinMax($limit);

        $stop = $this->adjustStop($limit);
        $stop += $this->adjustStopByVolatility($volatilityAverage);
        $stop += $this->adjustStopByATR($atr);

        $stop = $this->adjustStopMinMax($stop);

        [$limit, $stop] = $this->adjustLimitStopDiff($limit, $stop);

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

        $adjustment = ($volatilityAverage - $this->config['volatilityThreshold']) * $this->config['volatilityFactor'];

        return min($adjustment, $this->config['limitMax'] - $this->config['limitMin']);
    }

    /**
     * @return float
     */
    protected function adjustLimitByVolumeImbalance(): float
    {
        $volumeImbalance10 = $this->calculateVolumeImbalance(10);
        $volumeImbalance20 = $this->calculateVolumeImbalance(20);

        $adjustment = 0.0;

        if (abs($volumeImbalance10) > $this->config['volumeImbalance10Threshold']) {
            $adjustment += $this->config['limitIncrement'] * ($volumeImbalance10 / 100) * $this->config['volumeImbalanceWeight10'];
        }

        if (abs($volumeImbalance20) > $this->config['volumeImbalance20Threshold']) {
            $adjustment += $this->config['limitIncrement'] * ($volumeImbalance20 / 100) * $this->config['volumeImbalanceWeight20'];
        }

        return $adjustment;
    }

    /**
     * @param float $rsi
     *
     * @return float
     */
    protected function adjustLimitByRSI(float $rsi): float
    {
        if ($rsi > $this->config['rsiOverbought']) {
            return -$this->config['rsiLimitAdjust'];
        }

        if ($rsi < $this->config['rsiOversold']) {
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
        if (($sma50 > $sma200) && ($this->priceCurrent > $sma50)) {
            return $this->config['smaAdjust'] * 2;
        }

        if (($sma50 < $sma200) && ($this->priceCurrent < $sma50)) {
            return -($this->config['smaAdjust'] * 2);
        }

        if ($this->priceCurrent > $sma50) {
            return $this->config['smaAdjust'];
        }

        if ($this->priceCurrent < $sma50) {
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
        if ($macd > $macd_signal) {
            return $this->config['macdAdjust'];
        }

        if ($macd < $macd_signal) {
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
     * @param float $atr
     *
     * @return float
     */
    protected function adjustStopByATR(float $atr): float
    {
        return ($atr / $this->priceCurrent) * 100;
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
     * @param array $band
     *
     * @return float
     */
    protected function adjustLimitByBollingerBands(array $band): float
    {
        if ($this->priceCurrent >= $band['upper']) {
            return -$this->config['bollingerAdjust'];
        }

        if ($this->priceCurrent <= $band['lower']) {
            return $this->config['bollingerAdjust'];
        }

        return 0.0;
    }

    /**
     * @param float $stochastic
     *
     * @return float
     */
    protected function adjustLimitByStochastic(float $stochastic): float
    {
        if ($stochastic > 80) {
            return -$this->config['stochasticAdjust'];
        }

        if ($stochastic < 20) {
            return $this->config['stochasticAdjust'];
        }

        return 0.0;
    }

    /**
     * @param float $adx
     *
     * @return float
     */
    protected function adjustLimitByADX(float $adx): float
    {
        if ($adx > 25) {
            return $this->config['adxAdjust'];
        }

        return -$this->config['adxAdjust'];
    }

    /**
     * @param float $ao
     *
     * @return float
     */
    protected function adjustLimitByAO(float $ao): float
    {
        if ($ao > 0) {
            return $this->config['aoAdjust'];
        }

        return -$this->config['aoAdjust'];
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitByPriceMinMax(float $limit): float
    {
        return (strtolower($this->type) === 'sell')
            ? $this->adjustLimitByPriceMinMaxSell($limit)
            : $this->adjustLimitByPriceMinMaxBuy($limit);
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitByPriceMinMaxBuy(float $limit): float
    {
        $limitPrice = $this->priceCurrent * (1 - $limit / 100);
        $minPrice = min($this->pricesLow);

        if ($limitPrice >= $minPrice) {
            return $limit;
        }

        return (1 - ($minPrice / $this->priceCurrent)) * 100;
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitByPriceMinMaxSell(float $limit): float
    {
        $limitPrice = $this->priceCurrent * (1 + $limit / 100);
        $maxPrice = max($this->pricesHigh);

        if ($limitPrice <= $maxPrice) {
            return $limit;
        }

        return (($maxPrice / $this->priceCurrent) - 1) * 100;
    }

    /**
     * @param float $limit
     *
     * @return float
     */
    protected function adjustLimitMinMax(float $limit): float
    {
        if ($limit < $this->config['limitMin']) {
            return $this->config['limitMin'];
        }

        if ($limit > $this->config['limitMax']) {
            return $this->config['limitMax'];
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
     * @param array $pricesHigh
     * @param array $pricesLow
     * @param array $closePrices
     * @param int $period = 14
     *
     * @return array
     */
    public function atr(array $pricesHigh, array $pricesLow, array $closePrices, int $period = 14): array
    {
        $tr = [];
        $count = count($closePrices);

        for ($i = 1; $i < $count; $i++) {
            $tr[] = max(
                $pricesHigh[$i] - $pricesLow[$i],
                abs($pricesHigh[$i] - $closePrices[$i - 1]),
                abs($pricesLow[$i] - $closePrices[$i - 1])
            );
        }

        $count = count($tr);

        if ($count < $period) {
            return [];
        }

        $atr = [array_sum(array_slice($tr, 0, $period)) / $period];

        for ($i = $period; $i < $count; $i++) {
            $atr[] = (($atr[$i - $period] * ($period - 1)) + $tr[$i]) / $period;
        }

        return $atr;
    }

    /**
     * @param array $prices
     * @param int $period = 20
     * @param float $stdDev = 2
     *
     * @return array
     */
    public function bollingerBands(array $prices, int $period = 20, float $stdDev = 2): array
    {
        $sma = $this->sma($prices, $period);
        $bands = [];
        $count = count($prices);

        for ($i = $period - 1; $i < $count; $i++) {
            $slice = array_slice($prices, $i - $period + 1, $period);
            $mean = $sma[$i - $period + 1];
            $variance = array_sum(array_map(fn ($price) => pow($price - $mean, 2), $slice)) / $period;
            $std = sqrt($variance);

            $bands[] = [
                'upper' => $mean + ($stdDev * $std),
                'middle' => $mean,
                'lower' => $mean - ($stdDev * $std),
            ];
        }

        return $bands;
    }

    /**
     * @param array $pricesHigh
     * @param array $pricesLow
     * @param array $closePrices
     * @param int $period = 14
     *
     * @return array
     */
    public function stochasticOscillator(array $pricesHigh, array $pricesLow, array $closePrices, int $period = 14): array
    {
        $stochastic = [];
        $count = count($closePrices);

        for ($i = $period - 1; $i < $count; $i++) {
            $highestHigh = max(array_slice($pricesHigh, $i - $period + 1, $period));
            $lowestLow = min(array_slice($pricesLow, $i - $period + 1, $period));
            $currentClose = $closePrices[$i];

            if ($highestHigh === $lowestLow) {
                $stochastic[] = 0;
            } else {
                $stochastic[] = 100 * (($currentClose - $lowestLow) / ($highestHigh - $lowestLow));
            }
        }

        return $stochastic;
    }

    /**
     * @param array $pricesHigh
     * @param array $pricesLow
     * @param array $pricesClose
     * @param int $period
     *
     * @return array
     */
    public function adx(array $pricesHigh, array $pricesLow, array $pricesClose, int $period = 14): array
    {
        $count = count($pricesClose);

        if ($count <= $period) {
            return [];
        }

        $plusDM = [];
        $minusDM = [];
        $tr = [];

        for ($i = 1; $i < $count; $i++) {
            $upMove = $pricesHigh[$i] - $pricesHigh[$i - 1];
            $downMove = $pricesLow[$i - 1] - $pricesLow[$i];

            $plusDM[] = (($upMove > $downMove) && ($upMove > 0)) ? $upMove : 0;
            $minusDM[] = (($downMove > $upMove) && ($downMove > 0)) ? $downMove : 0;

            $tr[] = max(
                $pricesHigh[$i] - $pricesLow[$i],
                abs($pricesHigh[$i] - $pricesClose[$i - 1]),
                abs($pricesLow[$i] - $pricesClose[$i - 1])
            );
        }

        $atr = $this->smoothedMovingAverage($tr, $period);
        $smoothedPlusDM = $this->smoothedMovingAverage($plusDM, $period);
        $smoothedMinusDM = $this->smoothedMovingAverage($minusDM, $period);

        $dx = [];
        $count = count($atr);

        for ($i = 0; $i < $count; $i++) {
            $plusDI = 100 * ($smoothedPlusDM[$i] / $atr[$i]);
            $minusDI = 100 * ($smoothedMinusDM[$i] / $atr[$i]);
            $dx[] = 100 * (abs($plusDI - $minusDI) / ($plusDI + $minusDI));
        }

        return $this->smoothedMovingAverage($dx, $period);
    }

    /**
     * @param array $values
     * @param int $period
     *
     * @return array
     */
    public function smoothedMovingAverage(array $values, int $period): array
    {
        $sma = [];
        $count = count($values);

        if ($count < $period) {
            return $sma;
        }

        $sum = array_sum(array_slice($values, 0, $period));
        $prevSMA = $sum / $period;
        $sma[] = $prevSMA;

        for ($i = $period; $i < $count; $i++) {
            $currentSMA = ($prevSMA * ($period - 1) + $values[$i]) / $period;
            $sma[] = $prevSMA = $currentSMA;
        }

        return $sma;
    }

    /**
     * @param array $pricesHigh
     * @param array $pricesLow
     *
     * @return array
     */
    public function awesomeOscillator(array $pricesHigh, array $pricesLow): array
    {
        $medianPrices = array_map(static fn ($high, $low) => ($high + $low) / 2, $pricesHigh, $pricesLow);

        $sma5 = $this->sma($medianPrices, 5);
        $sma34 = $this->sma($medianPrices, 34);

        $alignedLength = min(count($sma5), count($sma34));
        $sma5 = array_slice($sma5, -$alignedLength);
        $sma34 = array_slice($sma34, -$alignedLength);

        return array_map(static fn ($sma5Val, $sma34Val) => $sma5Val - $sma34Val, $sma5, $sma34);
    }

    /**
     * @return array
     */
    public function indicators(): array
    {
        $prices5m = $this->prices['5minute'];
        $prices15m = $this->prices['15minute'];

        $indicators = [];

        $rsi = $this->rsi($prices5m);
        $indicators['rsi'] = $this->round(end($rsi) ?? 0, 4);

        $sma50 = $this->sma($prices5m, 50);
        $sma200 = $this->sma($prices5m, 200);
        $decimals = $this->decimals($prices5m);

        $indicators['sma_50'] = $this->round(end($sma50) ?? 0, $decimals);
        $indicators['sma_200'] = $this->round(end($sma200) ?? 0, $decimals);

        $macd = $this->macd($prices5m);

        $indicators['macd'] = $this->round(end($macd['macd']) ?? 0, 4);
        $indicators['macd_signal'] = $this->round(end($macd['signal']) ?? 0, 4);
        $indicators['macd_hist'] = $this->round(end($macd['histogram']) ?? 0, 4);

        $volatility5m = $this->volatility($prices5m, 5);
        $volatility15m = $this->volatility($prices15m, 15);
        $volatilityAverage = ($volatility5m + $volatility15m) / 2;

        $indicators['volatility_5m'] = $this->round($volatility5m, 4);
        $indicators['volatility_15m'] = $this->round($volatility15m, 4);
        $indicators['volatility_average'] = $this->round($volatilityAverage, 4);

        $atrValues = $this->atr($this->pricesHigh, $this->pricesLow, $prices5m);
        $indicators['atr'] = $this->round(end($atrValues) ?? 0, 4);

        $bollingerBands = $this->bollingerBands($prices5m);
        $indicators['bollinger_bands'] = $bollingerBands;

        $stochasticValues = $this->stochasticOscillator($this->pricesHigh, $this->pricesLow, $prices5m);
        $indicators['stochastic'] = $this->round(end($stochasticValues) ?? 0, 4);

        $adxValues = $this->adx($this->pricesHigh, $this->pricesLow, $prices5m);
        $indicators['adx'] = $this->round(end($adxValues) ?? 0, 4);

        $aoValues = $this->awesomeOscillator($this->pricesHigh, $this->pricesLow);
        $indicators['ao'] = $this->round(end($aoValues) ?? 0, 4);

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
     * @param int $minutes
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
            $returns[] = log($prices[$i] / $prices[$i - 1]);
        }

        $count = count($returns);
        $meanReturn = array_sum($returns) / $count;

        $variance = array_sum(array_map(static fn ($x) => pow($x - $meanReturn, 2), $returns));
        $variance = $variance / ($count - 1);

        $stdDev = sqrt($variance);

        $periodsPerDay = (24 * 60) / $minutes;
        $dailyVolatility = $stdDev * sqrt($periodsPerDay);

        return $dailyVolatility * 100;
    }
}
