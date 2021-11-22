<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Request;

class Auth extends RequestAbstract
{
    /**
     * @var string
     */
    protected string $timestamp;

    /**
     * @return mixed
     */
    public function send()
    {
        $this->timestamp = (string)(time() * 1000);

        return $this->client()
            ->setHeaders($this->headers())
            ->send()
            ->getBody('object');
    }

    /**
     * @return array
     */
    protected function headers(): array
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
        return $this->timestamp
            .strtoupper($this->method)
            .$this->path
            .($this->query ? ('?'.http_build_query($this->query)) : '')
            .($this->post ? json_encode($this->post) : '');
    }
}
