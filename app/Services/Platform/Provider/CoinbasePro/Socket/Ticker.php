<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Socket;

use Closure;
use stdClass;
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
     * @return self
     */
    public function open(): self
    {
        $this->socket = (new Websocket($this->config['socket']))
            ->setLog(config('logging.channels.websocket.enabled'))
            ->open();

        return $this;
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
            if ($this->valueIsValid($value)) {
                $callback($this->resource($value));
            }
        });

        return $this;
    }

    /**
     * @param ?\stdClass $value
     *
     * @return bool
     */
    protected function valueIsValid(?stdClass $value): bool
    {
        return $value && ($value->type === 'ticker') && $value->price && isset($value->time);
    }

    /**
     * @param \stdClass $value
     *
     * @return \App\Services\Platform\Resource\Exchange
     */
    protected function resource(stdClass $value): ExchangeResource
    {
        return new ExchangeResource([
            'code' => $value->product_id,
            'price' => (float)$value->price,
            'createdAt' => $value->time,
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
            'type' => 'subscribe',
            'product_ids' => $product_ids,
            'channels' => ['ticker'],
        ]);
    }
}
