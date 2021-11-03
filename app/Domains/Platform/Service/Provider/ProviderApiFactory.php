<?php declare(strict_types=1);

namespace App\Domains\Platform\Service\Provider;

use App\Domains\Platform\Model\Platform as Model;
use App\Services\Platform\ApiFactoryAbstract;

class ProviderApiFactory
{
    /**
     * @param \App\Domains\Platform\Model\Platform $row
     * @param array $config = []
     *
     * @return \App\Services\Platform\ApiFactoryAbstract
     */
    public static function get(Model $row, array $config = []): ApiFactoryAbstract
    {
        return static::new(static::class($row->code), static::config($row, $config));
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

    /**
     * @param string $code
     *
     * @return string
     */
    protected static function class(string $code): string
    {
        return '\\App\\Services\\Platform\\Provider\\'.studly_case($code).'\\Api';
    }

    /**
     * @param string $class
     * @param array $config
     *
     * @return \App\Services\Platform\ApiFactoryAbstract
     */
    protected static function new(string $class, array $config): ApiFactoryAbstract
    {
        return new $class($config);
    }
}
