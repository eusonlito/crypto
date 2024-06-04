<?php declare(strict_types=1);

namespace App\Services\Helper\Traits;

trait Number
{
    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     * @param ?string $default = '-'
     *
     * @return ?string
     */
    public function number(float|string|null $value, ?int $decimals = null, ?string $default = '-'): ?string
    {
        if ($value === null) {
            return $default;
        }

        return number_format(floatval($value), $this->numberDecimals($value, $decimals), ',', '.');
    }

    /**
     * @param float|string|null $value
     *
     * @return string
     */
    public function numberString(float|string|null $value): string
    {
        if (preg_match('/^[0-9]+(\.([0-9]+))?E\-([0-9]+)/i', (string)$value, $matches) === 0) {
            return (string)$value;
        }

        return number_format(floatval($value), strlen($matches[2]) + (int)$matches[3], '.', '');
    }

    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     *
     * @return int
     */
    public function numberDecimals(float|string|null $value, ?int $decimals = null): int
    {
        if ($decimals !== null) {
            return $decimals;
        }

        $value = abs((float)$value);

        if ($value === 0.0) {
            return 2;
        }

        if ($value > 10) {
            return 2;
        }

        if ($value > 1) {
            return 3;
        }

        if ($value > 0.1) {
            return 4;
        }

        if ($value > 0.01) {
            return 5;
        }

        if ($value > 0.0001) {
            return 6;
        }

        return 8;
    }

    /**
     * @param float|string|null $value
     * @param int $decimals
     *
     * @return float
     */
    public function roundFixed(float|string|null $value, int $decimals): float
    {
        if (empty($value)) {
            return 0;
        }

        $expo = pow(10, $decimals);

        if ($expo === 0.0) {
            return 0;
        }

        return intval((float)$value * $expo) / $expo;
    }

    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     * @param string $symbol = '€'
     *
     * @return ?string
     */
    public function money(float|string|null $value, ?int $decimals = null, string $symbol = '€'): ?string
    {
        return $this->number($value, $decimals).' '.$symbol;
    }

    /**
     * @param float $first
     * @param float $second
     * @param bool $float = true
     * @param bool $abs = false
     *
     * @return string|float
     */
    public function percent(float $first, float $second, bool $float = true, bool $abs = false)
    {
        if ($first && $second) {
            $value = round(($second - $first) / (($second + $first) / 2) * 100, 2);
        } else {
            $value = 0;
        }

        if ($abs) {
            $value = abs($value);
        }

        if ($float) {
            return $value;
        }

        if ($value) {
            return $this->number($value, 2).'%';
        }

        return '-%';
    }

    /**
     * @param int $bytes
     * @param int $decimals = 2
     *
     * @return string
     */
    public function sizeHuman(int $bytes, int $decimals = 2): string
    {
        $e = floor(log($bytes, 1024));
        $expo = pow(1024, $e);

        if ($expo === 0.0) {
            return '0 B';
        }

        $size = round($bytes / $expo, $decimals);
        $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$e];

        return $this->number($size, $decimals).' '.$unit;
    }

    /**
     * @param int $meters
     * @param int $decimals = 2
     *
     * @return string
     */
    public function distanceHuman(int $meters, int $decimals = 2): string
    {
        if ($meters >= 1000) {
            $meters /= 1000;
            $units = 'km';
        } else {
            $decimals = 0;
            $units = 'm';
        }

        return $this->number($meters, $decimals).' '.$units;
    }
}
