<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Request;

use App\Services\Http\Curl\Curl;

abstract class RequestAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var string
     */
    protected string $endpoint;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var array
     */
    protected array $query = [];

    /**
     * @var array
     */
    protected array $post = [];

    /**
     * @var int
     */
    protected int $cache = 0;

    /**
     * @var bool
     */
    protected bool $log = false;

    /**
     * @return mixed
     */
    abstract public function send();

    /**
     * @param array $config = []
     *
     * @var self
     */
    public function __construct(array $config = [])
    {
        $this->config($config);
    }

    /**
     * @param array $config
     *
     * @var self
     */
    public function config(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string $method
     *
     * @var self
     */
    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $endpoint
     *
     * @var self
     */
    public function endpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * @param string $path
     *
     * @var self
     */
    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param array $query
     *
     * @var self
     */
    public function query(array $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param array $post
     *
     * @var self
     */
    public function post(array $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @param int $cache
     *
     * @var self
     */
    public function cache(int $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @param bool $log
     *
     * @return self
     */
    public function log(bool $log): self
    {
        $this->log = $log;

        return $this;
    }

    /**
     * @return \App\Services\Http\Curl\Curl
     */
    protected function client(): Curl
    {
        return (new Curl())
            ->setException(true)
            ->setLog($this->log)
            ->setJson()
            ->setMethod($this->method)
            ->setUrl($this->endpoint.$this->path)
            ->setQuery($this->query)
            ->setBody($this->post)
            ->setCache($this->cache, 'binance');
    }
}
