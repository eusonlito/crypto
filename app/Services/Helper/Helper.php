<?php declare(strict_types=1);

namespace App\Services\Helper;

use App\Exceptions\NotFoundException;

class Helper
{
    /**
     * @param int $length
     * @param bool $safe = false
     * @param bool $lower = false
     *
     * @return string
     */
    public function uniqidReal(int $length, bool $safe = false, bool $lower = false): string
    {
        if ($safe) {
            $string = '23456789bcdfghjkmnpqrstwxyzBCDFGHJKMNPQRSTWXYZ';
        } else {
            $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($lower) {
            $string = strtolower($string);
        }

        return substr(str_shuffle(str_repeat($string, rand((int)($length / 2), $length))), 0, $length);
    }

    /**
     * @param string $string
     * @param int $prefix = 0
     * @param int $suffix = 0
     *
     * @return string
     */
    public function slug(string $string, int $prefix = 0, int $suffix = 0): string
    {
        if ($prefix) {
            $string = $this->uniqidReal($prefix, true).'-'.$string;
        }

        if ($suffix) {
            $string .= '-'.$this->uniqidReal($suffix, true);
        }

        return str_slug($string);
    }

    /**
     * @param mixed $value
     *
     * @return ?string
     */
    public function jsonEncode($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * @param array $array
     * @param array $keys
     *
     * @return array
     */
    public function arrayKeysWhitelist(array $array, array $keys): array
    {
        return array_intersect_key($array, array_flip($keys));
    }

    /**
     * @param array $array
     * @param array $keys
     *
     * @return array
     */
    public function arrayKeysBlacklist(array $array, array $keys): array
    {
        return array_diff_key($array, array_flip($keys));
    }

    /**
     * @param array $array
     * @param array $values
     *
     * @return array
     */
    public function arrayValuesWhitelist(array $array, array $values): array
    {
        return array_intersect($array, $values);
    }

    /**
     * @param array $array
     * @param array $values
     *
     * @return array
     */
    public function arrayValuesBlacklist(array $array, array $values): array
    {
        return array_diff($array, $values);
    }

    /**
     * @param array $query
     *
     * @return string
     */
    public function query(array $query): string
    {
        return http_build_query($query + request()->query());
    }

    /**
     * @param ?float $value
     * @param ?int $decimals = null
     * @param ?string $default = '-'
     *
     * @return ?string
     */
    public function number(?float $value, ?int $decimals = null, ?string $default = '-'): ?string
    {
        if ($value === null) {
            return $default;
        }

        return number_format($value, $this->numberDecimals($value, $decimals), ',', '.');
    }

    /**
     * @param float $value
     *
     * @return string
     */
    public function numberString(float $value): string
    {
        if (preg_match('/^[0-9]+(\.([0-9]+))?E\-([0-9]+)/i', (string)$value, $matches) === 0) {
            return (string)$value;
        }

        return number_format($value, strlen($matches[2]) + (int)$matches[3], '.', '');
    }

    /**
     * @param float $value
     * @param ?int $decimals = null
     *
     * @return int
     */
    public function numberDecimals(float $value, ?int $decimals = null): int
    {
        if ($decimals !== null) {
            return $decimals;
        }

        $value = abs($value);

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

        if ($value > 0.00001) {
            return 6;
        }

        return 8;
    }

    /**
     * @param float $value
     * @param ?int $decimals = null
     *
     * @return string
     */
    public function money(float $value, ?int $decimals = null): string
    {
        return $this->number($value, $decimals).'€';
    }

    /**
     * @param float $value
     * @param int $decimals
     *
     * @return float
     */
    public function roundFixed(float $value, int $decimals): float
    {
        $expo = pow(10, $decimals);

        return intval($value * $expo) / $expo;
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
            $value = round(($second * 100 / $first) - 100, 2);
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
     * @param string $date
     *
     * @return ?string
     */
    public function dateToDate(string $date): ?string
    {
        if (empty($date)) {
            return $date;
        }

        [$day, $time] = explode(' ', $date) + ['', ''];

        if (strpos($day, ':')) {
            [$day, $time] = [$time, $day];
        }

        if (!preg_match('#^[0-9]{1,4}[/\-][0-9]{1,2}[/\-][0-9]{1,4}$#', $day)) {
            return null;
        }

        if ($time) {
            if (substr_count($time, ':') === 1) {
                $time .= ':00';
            }

            if (!preg_match('#^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$#', $time)) {
                return null;
            }
        }

        $day = preg_split('#[/\-]#', $day);

        if (strlen($day[0]) !== 4) {
            $day = array_reverse($day);
        }

        return trim(implode('-', $day).' '.$time);
    }

    /**
     * @param string $message = ''
     *
     * @throws \App\Exceptions\NotFoundException
     *
     * @return void
     */
    public function notFound(string $message = ''): void
    {
        throw new NotFoundException($message ?: __('common.error.not-found'));
    }
}
