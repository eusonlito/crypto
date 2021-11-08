<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Socket;

use Closure;
use stdClass;
use App\Services\Platform\Provider\Kucoin\Api;
use App\Services\Platform\Resource\Exchange as ExchangeResource;
use App\Services\Platform\SocketAbstract;
use App\Services\Websocket\Websocket;

class Ticker extends SocketAbstract
{
    /**
     * @var \App\Services\Websocket\Websocket
     */
    protected Websocket $socket;

    /**
     * @var string
     */
    protected string $endpont;

    /**
     * @var string
     */
    protected string $token;

    /**
     * @var int
     */
    protected int $time;

    /**
     * @return self
     */
    public function open(): self
    {
        $this->time = time();

        $this->socket = $this->websocket()
            ->setLog(config('logging.channels.websocket.enabled'))
            ->open();

        return $this;
    }

    /**
     * @return \App\Services\Websocket\Websocket
     */
    protected function websocket(): Websocket
    {
        $endpoint = parse_url($this->endpoint());

        return new Websocket($endpoint['scheme'].'://'.$endpoint['host'], $endpoint['path'].'?'.$endpoint['query']);
    }

    /**
     * @return string
     */
    protected function endpoint(): string
    {
        $response = (new Api($this->config))->websockets();

        return current($response->instanceServers)->endpoint.'?'.http_build_query([
            'token' => $response->token,
            'connectId' => uniqid(),
        ]);
    }

    /**
     * @param array $product_ids
     *
     * @return self
     */
    public function subscribe(array $product_ids): self
    {
        $this->socket->write($this->subscribeMessage($product_ids));

        return $this;
    }

    /**
     * @param \Closure $callback
     *
     * @return self
     */
    public function read(Closure $callback): self
    {
        $this->socket->read(function ($value) use ($callback) {
            $this->ping();

            if ($this->valueIsValid($value)) {
                $callback($this->resource($value));
            }
        });

        return $this;
    }

    /**
     * @return void
     */
    protected function ping(): void
    {
        if ((time() - $this->time) < 30) {
            return;
        }

        $this->socket->write($this->pingMessage());
        $this->time = time();
    }

    /**
     * @param ?\stdClass $value
     *
     * @return bool
     */
    protected function valueIsValid(?stdClass $value): bool
    {
        return $value
            && isset($value->topic)
            && ($value->topic === '/market/ticker:all')
            && $value->data->price
            && isset($value->data->time);
    }

    /**
     * @param \stdClass $value
     *
     * @return \App\Services\Platform\Resource\Exchange
     */
    protected function resource(stdClass $value): ExchangeResource
    {
        return new ExchangeResource([
            'code' => $value->subject,
            'price' => (float)$value->data->price,
            'createdAt' => (string)$value->data->time,
        ]);
    }

    /**
     * @param array $product_ids
     *
     * @return string
     */
    protected function subscribeMessage(array $product_ids): string
    {
        return json_encode([
            'id' => time(),
            'type' => 'subscribe',
            'topic' => '/market/ticker:all',
            'response' => true,
        ]);
    }

    /**
     * @return string
     */
    protected function pingMessage(): string
    {
        return json_encode([
            'id' => time(),
            'type' => 'ping',
        ]);
    }
}
