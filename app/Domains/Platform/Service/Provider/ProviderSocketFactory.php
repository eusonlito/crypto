<?php declare(strict_types=1);

namespace App\Domains\Platform\Service\Provider;

use App\Domains\Platform\Model\Platform as Model;
use App\Services\Platform\SocketAbstract;

class ProviderSocketFactory
{
    /**
     * @param \App\Domains\Platform\Model\Platform $row
     * @param string $name
     * @param array $config = []
     *
     * @return ?\App\Services\Platform\SocketAbstract
     */
    public static function get(Model $row, string $name, array $config = []): ?SocketAbstract
    {
        return static::new(static::class($row->code, $name), static::config($row, $config));
    }

    /**
     * @param string $code
     * @param string $name
     *
     * @return string
     */
    protected static function class(string $code, string $name): string
    {
        return '\\App\\Services\\Platform\\Provider\\'.studly_case($code).'\\Socket\\'.$name;
    }

    /**
     * @param string $class
     * @param array $config
     *
     * @return ?\App\Services\Platform\SocketAbstract
     */
    protected static function new(string $class, array $config): ?SocketAbstract
    {
        return class_exists($class) ? new $class($config) : null;
    }

    /**
     * @param \App\Domains\Platform\Model\Platform $row
     * @param array $config
     *
     * @return array
     */
    protected static function config(Model $row, array $config): array
    {
        $config += config('platform.'.$row->code);

        if ($row->relationLoaded('userPivot') && $row->userPivot) {
            $config = $row->userPivot->settings + $config;
        }

        return $config;
    }
}
