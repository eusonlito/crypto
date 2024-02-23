<?php declare(strict_types=1);

namespace App\Services\Html;

use Illuminate\Support\Collection;

class Html
{
    /**
     * @var array
     */
    protected static array $query;

    /**
     * @param ?string $path
     *
     * @return string
     */
    public static function asset(?string $path): string
    {
        static $cache = [];

        if (empty($path)) {
            return '';
        }

        if (str_starts_with($path, 'data:')) {
            return $path;
        }

        if (isset($cache[$path])) {
            return $cache[$path];
        }

        if (is_file($file = public_path($path))) {
            $path .= '?v'.filemtime($file);
        }

        return $cache[$path] = asset($path);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function assetManifest(string $path): string
    {
        if (config('app.debug')) {
            return $path;
        }

        $manifest = public_path('build/rev-manifest.json');

        if (is_file($manifest) === false) {
            return asset($path);
        }

        return json_decode(file_get_contents($manifest), true)[$path] ?? asset($path);
    }

    /**
     * @param ?string $path
     * @param bool $cache = true
     * @param bool $image = false
     *
     * @return string
     */
    public static function inline(?string $path, bool $cache = true, bool $image = false): string
    {
        static $cache = [];

        if (empty($path)) {
            return '';
        }

        if ($cache && isset($cache[$path])) {
            return $cache[$path];
        }

        $file = public_path($path);
        $contents = is_file($file) ? file_get_contents($file) : '';

        if ($image) {
            $contents = 'data:image/'.pathinfo($file, PATHINFO_EXTENSION).';base64,'.base64_encode($contents);
        }

        return $cache ? ($cache[$path] = $contents) : $contents;
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

    /**
     * @param float $percent
     * @param string $class = 'h-3'
     *
     * @return string
     */
    public static function progressbar(float $percent, string $class = 'h-3'): string
    {
        if ($percent >= 90) {
            $color = '#F15B38';
        } elseif ($percent >= 70) {
            $color = '#EDBE38';
        } else {
            $color = '#1E3A8A';
        }

        $html = trim('
            <div class="w-full bg-slate-200 rounded overflow-hidden :class">
                <div role="progressbar" aria-valuenow=":percent" aria-valuemin="0" aria-valuemax="100" class="h-full rounded flex justify-center items-center" style="background-color: :color; width: :percent%"></div>
            </div>
        ');

        return strtr($html, [
            ':class' => $class,
            ':percent' => $percent,
            ':color' => $color,
        ]);
    }
}
