<?php declare(strict_types=1);

namespace App\Services\Trader;

class Trader
{
    /**
     * @param array $data
     * @param int $period
     *
     * @return array
     */
    public static function sma(array $data, int $period): array
    {
        $sma = [];
        $count = count($data);

        if ($count < $period) {
            return $sma;
        }

        $sum = array_sum(array_slice($data, 0, $period));
        $sma[] = $sum / $period;

        for ($i = $period; $i < $count; $i++) {
            $sum = $sum - $data[$i - $period] + $data[$i];
            $sma[] = $sum / $period;
        }

        return $sma;
    }

    /**
     * @param array $data
     * @param int $period
     *
     * @return array
     */
    public static function rsi(array $data, int $period = 14): array
    {
        $rsi = [];
        $count = count($data);

        if ($count <= $period) {
            return $rsi;
        }

        $gains = $losses = [];

        for ($i = 1; $i <= $period; $i++) {
            $delta = $data[$i] - $data[$i - 1];
            $gains[] = $delta > 0 ? $delta : 0;
            $losses[] = $delta < 0 ? abs($delta) : 0;
        }

        $avgGain = array_sum($gains) / $period;
        $avgLoss = array_sum($losses) / $period;
        $rsi[] = empty($avgLoss) ? 100 : 100 - (100 / (1 + ($avgGain / $avgLoss)));

        for ($i = $period + 1; $i < $count; $i++) {
            $delta = $data[$i] - $data[$i - 1];
            $gain = $delta > 0 ? $delta : 0;
            $loss = $delta < 0 ? abs($delta) : 0;

            $avgGain = (($avgGain * ($period - 1)) + $gain) / $period;
            $avgLoss = (($avgLoss * ($period - 1)) + $loss) / $period;

            $rsi[] = empty($avgLoss) ? 100 : 100 - (100 / (1 + ($avgGain / $avgLoss)));
        }

        return $rsi;
    }

    /**
     * @param array $data
     * @param int $period
     *
     * @return array
     */
    public static function ema(array $data, int $period): array
    {
        $ema = [];
        $count = count($data);

        if ($count < $period) {
            return $ema;
        }

        $k = 2 / ($period + 1);
        $sma = array_sum(array_slice($data, 0, $period)) / $period;
        $ema[] = $sma;
        $previous = $sma;

        for ($i = $period; $i < $count; $i++) {
            $current = ($data[$i] - $previous) * $k + $previous;
            $ema[] = $current;
            $previous = $current;
        }

        return $ema;
    }

    /**
     * @param array $data
     * @param int $shortPeriod
     * @param int $longPeriod
     * @param int $signalPeriod
     *
     * @return array
     */
    public static function macd(
        array $data,
        int $shortPeriod = 12,
        int $longPeriod = 26,
        int $signalPeriod = 9
    ): array {
        $emaShort = static::ema($data, $shortPeriod);
        $emaLong = static::ema($data, $longPeriod);

        $alignedLength = min(count($emaShort), count($emaLong));
        $emaShort = array_slice($emaShort, -$alignedLength);
        $emaLong = array_slice($emaLong, -$alignedLength);

        $macdLine = array_map(static fn ($short, $long) => $short - $long, $emaShort, $emaLong);

        $signalLine = static::ema($macdLine, $signalPeriod);

        $alignedLength = min(count($macdLine), count($signalLine));
        $macdLine = array_slice($macdLine, -$alignedLength);
        $signalLine = array_slice($signalLine, -$alignedLength);

        $histogram = array_map(static fn ($macd, $signal) => $macd - $signal, $macdLine, $signalLine);

        return [
            'macd' => $macdLine,
            'signal' => $signalLine,
            'histogram' => $histogram,
        ];
    }

    /**
     * @param array $data
     *
     * @return float
     */
    public static function volatility(array $data): float
    {
        $count = count($data);

        if ($count < 2) {
            return 0.0;
        }

        $n = $count - 1;
        $sumReturns = 0.0;
        $sumSquaredReturns = 0.0;

        for ($i = 1; $i < $count; $i++) {
            $return = log($data[$i] / $data[$i - 1]);
            $sumReturns += $return;
            $sumSquaredReturns += $return * $return;
        }

        if ($n < 2) {
            return 0.0;
        }

        $meanReturn = $sumReturns / $n;
        $variance = ($sumSquaredReturns - $n * pow($meanReturn, 2)) / ($n - 1);

        return sqrt($variance) * sqrt(252);
    }

    /**
     * @param array $prices
     *
     * @return array
     */
    public static function all(array $prices): array
    {
        $rsi = static::rsi($prices);
        $sma50 = static::sma($prices, 50);
        $sma200 = static::sma($prices, 200);
        $macd = static::macd($prices);
        $decimals = static::decimals($prices);

        return [
            'rsi' => static::round(end($rsi), 4),
            'sma_50' => static::round(end($sma50), $decimals),
            'sma_200' => static::round(end($sma200), $decimals),
            'macd' => static::round(end($macd['macd']), 4),
            'macd_signal' => static::round(end($macd['signal']), 4),
            'macd_hist' => static::round(end($macd['histogram']), 4),
            'volatility' => static::round(static::volatility($prices), 4),
        ];
    }

    /**
     * @param array $prices
     *
     * @return int
     */
    public static function decimals(array $prices): int
    {
        $decimals = [];

        foreach ($prices as $price) {
            $decimals[] = strlen(explode('.', strval($price))[1] ?? '');
        }

        return $decimals ? intval(array_sum($decimals) / count($decimals)) : 0;
    }

    /**
     * @param int|float|bool|null $value
     * @param int $decimals
     *
     * @return int|float|bool|null
     */
    public static function round(int|float|bool|null $value, int $decimals): int|float|bool|null
    {
        return is_float($value) ? round($value, $decimals) : $value;
    }
}
