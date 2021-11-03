<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version;

use Illuminate\Support\Collection;

class VersionCollectionFactory
{
    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return \App\Domains\Forecast\Service\Version\VersionCollectionAbstract
     */
    public static function get(Collection $list): VersionCollectionAbstract
    {
        return static::new(static::class('V1'), $list);
    }

    /**
     * @param string $version
     *
     * @return string
     */
    protected static function class(string $version): string
    {
        return __NAMESPACE__.'\\'.$version.'\\Collection';
    }

    /**
     * @param string $class
     * @param \Illuminate\Support\Collection $list
     *
     * @return \App\Domains\Forecast\Service\Version\VersionCollectionAbstract
     */
    protected static function new(string $class, Collection $list): VersionCollectionAbstract
    {
        return new $class($list);
    }
}
