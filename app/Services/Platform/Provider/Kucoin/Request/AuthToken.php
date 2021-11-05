<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

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
        $this->timestamp = (string)(time() * 1000);
    }

    /**
     * @return array
     */
    public function headers(): array
    {
        return [
            'KC-API-KEY' => $this->config['key'],
            'KC-API-PASSPHRASE' => $this->apiPassphrase(),
            'KC-API-SIGN' => $this->apiSign(),
            'KC-API-TIMESTAMP' => $this->timestamp,
            'KC-API-KEY-VERSION' => '2',
        ];
    }

    /**
     * @return string
     */
    protected function apiPassphrase(): string
    {
        return base64_encode(hash_hmac('SHA256', $this->config['passphrase'], $this->config['secret'], true));
    }

    /**
     * @return string
     */
    protected function apiSign(): string
    {
        return base64_encode(hash_hmac('SHA256', $this->apiSignString(), $this->config['secret'], true));
    }

    /**
     * @return string
     */
    protected function apiSignString(): string
    {
        return $this->timestamp.$this->method.$this->path.($this->payload ? json_encode($this->payload) : '');
    }
}
