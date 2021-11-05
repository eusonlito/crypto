<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Provider\Kucoin\Error;
use App\Services\Platform\Provider\Kucoin\Request\Auth as AuthRequest;
use App\Services\Platform\Provider\Kucoin\Request\Guest as GuestRequest;

abstract class ApiAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var string
     */
    protected string $endpoint;

    /**
     * @var string
     */
    protected string $prefix = '';

    /**
     * @param array $config
     *
     * @return self
     */
    public function config(array $config): self
    {
        $this->config = $config;
        $this->endpoint ??= $config['endpoint'];

        return $this;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $query = []
     * @param array $post = []
     * @param int $cache = 0
     *
     * @return mixed
     */
    public function requestAuth(string $method, string $path, array $query = [], array $post = [], int $cache = 0)
    {
        return Error::check(AuthRequest::send($this->config, $method, $this->endpoint, $this->requestPath($path, $query), $post, $cache));
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $query = []
     * @param array $post = []
     * @param int $cache = 0
     *
     * @return mixed
     */
    public function requestGuest(string $method, string $path, array $query = [], array $post = [], int $cache = 0)
    {
        return Error::check(GuestRequest::send($method, $this->endpoint, $this->requestPath($path, $query), $post, $cache));
    }

    /**
     * @param string $path
     * @param array $query = []
     *
     * @return string
     */
    protected function requestPath(string $path, array $query = []): string
    {
        return $this->prefix.$path.($query ? ('?'.http_build_query($query)) : '');
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function date(string $date): string
    {
        return date('Y-m-d H:i:s', strtotime($date));
    }

    /**
     * @param float $value
     *
     * @return string
     */
    protected function decimal(float $value): string
    {
        return helper()->numberString($value);
    }

    /**
     * @param array $rows
     * @param array $args
     *
     * @return \Illuminate\Support\Collection
     */
    protected function collection(array $rows, ...$args): Collection
    {
        $collection = [];

        foreach ($rows as $key => $row) {
            if ($resource = $this->resource($row, $key, ...$args)) {
                $collection[] = $resource;
            }
        }

        return collect($collection);
    }
}
