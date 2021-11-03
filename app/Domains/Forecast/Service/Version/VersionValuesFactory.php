<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version;

use Illuminate\Support\Collection;

class VersionValuesFactory
{
    /**
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return \App\Domains\Forecast\Service\Version\VersionValuesAbstract
     */
    public static function get(Collection $exchanges): VersionValuesAbstract
    {
        return static::new(static::class('V1'), $exchanges);
    }

    /**
     * @param string $version
     *
     * @return string
     */
    protected static function class(string $version): string
    {
        return __NAMESPACE__.'\\'.$version.'\\Values';
    }

    /**
     * @param string $class
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return \App\Domains\Forecast\Service\Version\VersionValuesAbstract
     */
    protected static function new(string $class, Collection $exchanges): VersionValuesAbstract
    {
        return new $class($exchanges);
    }
}
