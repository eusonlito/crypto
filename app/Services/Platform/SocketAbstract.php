<?php declare(strict_types=1);

namespace App\Services\Platform;

abstract class SocketAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @param array $config
     *
     * @return self
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
