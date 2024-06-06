<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Request;

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
        $this->timestamp = (string)time();

        return $this->client()
            ->setHeaders($headers = $this->headers())
            ->setLogHide(array_keys($headers))
            ->send()
            ->getBody('object');
    }

    /**
     * @return array
     */
    protected function headers(): array
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
        return $this->timestamp
            .strtoupper($this->method)
            .$this->path
            .($this->query ? ('?'.http_build_query($this->query)) : '')
            .($this->post ? json_encode($this->post) : '');
    }
}
