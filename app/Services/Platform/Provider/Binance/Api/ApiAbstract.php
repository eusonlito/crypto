<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Provider\Binance\Error;
use App\Services\Platform\Provider\Binance\Request\Auth as AuthRequest;
use App\Services\Platform\Provider\Binance\Request\Guest as GuestRequest;
use App\Services\Platform\Provider\Binance\Request\RequestAbstract;

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
     * @var bool
     */
    protected bool $log = false;

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
        return Error::check($this->request(new AuthRequest(), $method, $path, $query, $post, $cache));
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
        return Error::check($this->request(new GuestRequest(), $method, $path, $query, $post, $cache));
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $post
     * @param int $cache
     *
     * @return mixed
     */
    protected function request(RequestAbstract $request, string $method, string $path, array $query, array $post, int $cache)
    {
        return $request->config($this->config)
            ->method($method)
            ->endpoint($this->endpoint)
            ->path($this->prefix.$path)
            ->query($query)
            ->post($post)
            ->cache($cache)
            ->log(config('logging.channels.curl.enabled') ?: $this->log)
            ->send();
    }

    /**
     * @param int $timestamp
     *
     * @return string
     */
    protected function date(int $timestamp): string
    {
        return date('Y-m-d H:i:s', intval($timestamp / 1000));
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
