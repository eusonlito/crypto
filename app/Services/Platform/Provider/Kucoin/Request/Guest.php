<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

class Guest
{
    /**
     * @param string $method
     * @param string $endpoint
     * @param string $path
     * @param array $post = []
     * @param int $cache = 0
     *
     * @return mixed
     */
    public static function send(string $method, string $endpoint, string $path, array $post = [], int $cache = 0)
    {
        return Client::get($method, $endpoint.$path, $post, $cache)
            ->send()
            ->getBody('object');
    }
}
