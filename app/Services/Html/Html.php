<?php declare(strict_types=1);

namespace App\Services\Html;

use Illuminate\Support\Collection;

class Html
{
    /**
     * @var array
     */
    protected static array $asset = [];

    /**
     * @var array
     */
    protected static array $query;

    /**
     * @param string $path
     *
     * @return string
     */
    public static function asset(string $path): string
    {
        if (isset(static::$asset[$path])) {
            return static::$asset[$path];
        }

        if (is_file($file = public_path($path))) {
            $path .= '?'.filemtime($file);
        }

        return static::$asset[$path] = asset($path);
    }

    /**
     * @param string $name
     * @param string $class = ''
     *
     * @return string
     */
    public static function icon(string $name, string $class = ''): string
    {
        return '<svg class="feather '.$class.'"><use xlink:href="'.static::asset('build/images/feather-sprite.svg').'#'.$name.'" /></svg>';
    }

    /**
     * @param array $query
     *
     * @return string
     */
    public static function query(array $query): string
    {
        return helper()->query($query);
    }

    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     *
     * @return string
     */
    public static function number(float|string|null $value, ?int $decimals = null): string
    {
        return helper()->number($value, $decimals);
    }

    /**
     * @param float|string|null $value
     *
     * @return string
     */
    public static function numberString(float|string|null $value): string
    {
        return helper()->numberString($value);
    }

    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     *
     * @return string
     */
    public static function money(float|string|null $value, ?int $decimals = null): string
    {
        return helper()->money($value, $decimals);
    }

    /**
     * @param float $first
     * @param float $second
     *
     * @return string
     */
    public static function percent(float $first, float $second): string
    {
        return helper()->percent($first, $second, false);
    }

    /**
     * @param float|string|null $value
     * @param ?int $decimals = null
     *
     * @return string
     */
    public static function value(float|string|null $value, ?int $decimals = null): string
    {
        return number_format((float)$value, helper()->numberDecimals($value, $decimals), '.', '');
    }

    /**
     * @param float $value
     * @param ?bool $condition = null
     *
     * @return string
     */
    public static function numberColor(float $value, ?bool $condition = null): string
    {
        if ($condition === false) {
            return '';
        }

        if ($value > 0) {
            return 'text-theme-10';
        }

        if ($value < 0) {
            return 'text-theme-24';
        }

        return '';
    }

    /**
     * @param bool $status
     *
     * @return string
     */
    public static function status(bool $status): string
    {
        if ($status) {
            $theme = 'text-theme-10';
            $icon = 'check-square';
        } else {
            $theme = 'text-theme-24';
            $icon = 'square';
        }

        return '<span class="hidden">'.(int)$status.'</span>'
            .'<span class="flex items-center justify-center '.$theme.'">'.static::icon($icon, 'w-4 h-4 mr-2').'</span>';
    }

    /**
     * @param array $config
     * @param array $values
     *
     * @return string
     */
    public static function forecastValue(array $config, array $values): string
    {
        if ($config['list'] === false) {
            return '';
        }

        $value = $values[$config['key']] ?? null;

        if ($value === null) {
            return '<td>-</td>';
        }

        $class = '';

        switch ($config['format']) {
            case 'float':
                $print = helper()->number($value);
                break;

            case 'percent':
                $print = helper()->number($value).'%';
                $class = ($value > 0) ? 'text-theme-10' : 'text-theme-24';
                break;

            case 'bool':
                $print = static::status($value);
                break;

            default:
                $print = $value;
                break;
        }

        return '<td title="'.$value.' - '.$config['description'].'"><span class="block '.$class.'">'.$print.'</span></td>';
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return string
     */
    public static function orderBuySellTitle(Collection $list): string
    {
        return $list->map(static function ($value) {
            return helper()->number($value->amount).' ('.helper()->number($value->price).')';
        })->implode(' + ');
    }

    /**
     * @param array $sells
     *
     * @return string
     */
    public static function orderSellPendingTitle(array $sells): string
    {
        return implode(' + ', array_map(static function ($value) {
            return helper()->number($value['amount']).' ('.helper()->number($value['price']).')';
        }, $sells));
    }
}
