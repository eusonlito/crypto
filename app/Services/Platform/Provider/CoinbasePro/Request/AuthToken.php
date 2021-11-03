<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Request;

class AuthToken
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
    protected string $path;

    /**
     * @var array
     */
    protected array $payload;

    /**
     * @var string
     */
    protected string $timestamp;

    /**
     * @param array $config
     * @param string $method
     * @param string $path
     * @param array $payload
     *
     * @return self
     */
    public function __construct(array $config, string $method, string $path, array $payload)
    {
        $this->config = $config;
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->payload = $payload;
        $this->timestamp = (string)time();
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return [
            'CB-ACCESS-KEY' => $this->config['key'],
            'CB-ACCESS-PASSPHRASE' => $this->config['passphrase'],
            'CB-ACCESS-SIGN' => $this->accessSign(),
            'CB-ACCESS-TIMESTAMP' => $this->timestamp,
        ];
    }

    /**
     * @return string
     */
    protected function accessSign(): string
    {
        return base64_encode(hash_hmac('SHA256', $this->accessSignString(), base64_decode($this->config['secret']), true));
    }

    /**
     * @return string
     */
    protected function accessSignString(): string
    {
        return $this->timestamp.$this->method.$this->path.($this->payload ? json_encode($this->payload) : '');
    }
}
