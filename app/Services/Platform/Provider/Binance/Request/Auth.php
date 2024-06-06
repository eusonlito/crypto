<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Request;

class Auth extends RequestAbstract
{
    /**
     * @return mixed
     */
    public function send()
    {
        $this->sign();

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
            'X-MBX-APIKEY' => $this->config['key'],
        ];
    }

    /**
     * @return void
     */
    protected function sign(): void
    {
        if ($this->post) {
            $this->post = $this->data($this->post);
        } else {
            $this->query = $this->data($this->query);
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function data(array $data): array
    {
        $data['recvWindow'] = 10000;
        $data['timestamp'] = intval(microtime(true) * 1000);

        return $data + ['signature' => $this->signature($data)];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function signature(array $data): string
    {
        return hash_hmac('SHA256', http_build_query($data), $this->config['secret']);
    }
}
