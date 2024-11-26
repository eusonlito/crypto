<?php declare(strict_types=1);

namespace App\Services\Ai;

use App\Services\Http\Curl\Curl;

abstract class AiAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @return \App\Services\Http\Curl\Curl
     */
    protected function curl(): Curl
    {
        return Curl::new()
            ->setMethod('POST')
            ->setLog($this->requestLog())
            ->setCache($this->requestCache())
            ->setAuthorization($this->config['key'])
            ->setLogHide(['authorization'])
            ->setTimeOut(90)
            ->setException(true);
    }

    /**
     * @return int
     */
    protected function requestCache(): int
    {
        return app()->environment('local') ? (3600 * 24) : 0;
    }

    /**
     * @return bool
     */
    protected function requestLog(): bool
    {
        return app()->environment('local');
    }
}
