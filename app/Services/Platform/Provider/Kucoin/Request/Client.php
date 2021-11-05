<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

use App\Services\Http\Curl\Curl;

class Client
{
    /**
     * @param string $method
     * @param string $url
     * @param array $post = []
     * @param int $cache = 0
     *
     * @return \App\Services\Http\Curl\Curl
     */
    public static function get(string $method, string $url, array $post = [], int $cache = 0): Curl
    {
        return (new Curl())
            ->setException(true)
            ->setLog(config('logging.channels.curl.enabled'))
            ->setJson()
            ->setMethod($method)
            ->setUrl($url)
            ->setBody($post)
            ->setCache($cache, 'kucoin');
    }
}
