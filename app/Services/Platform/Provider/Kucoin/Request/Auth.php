<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

class Auth
{
    /**
     * @param array $config
     * @param string $method
     * @param string $endpoint
     * @param string $path
     * @param array $post = []
     * @param int $cache = 0
     *
     * @return mixed
     */
    public static function send(array $config, string $method, string $endpoint, string $path, array $post = [], int $cache = 0)
    {
        return Client::get($method, $endpoint.$path, $post, $cache)
            ->setHeaders((new AuthToken($config, $method, $path, $post))->headers())
            ->send()
            ->getBody('object');
    }
}
