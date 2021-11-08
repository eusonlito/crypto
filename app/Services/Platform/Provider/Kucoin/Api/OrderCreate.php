<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;
use App\Services\Platform\Resource\Order as OrderResource;
use App\Services\Platform\Provider\Kucoin\Api\Traits\OrderResource as OrderResourceTrait;

class OrderCreate extends ApiAbstract
{
    use OrderResourceTrait;

    /**
     * @var string
     */
    protected string $product;

    /**
     * @var string
     */
    protected string $side;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $data;

    /**
     * @var bool
     */
    protected bool $log = true;

    /**
     * @param string $product
     * @param string $side
     * @param string $type
     * @param array $data
     *
     * @return self
     */
    public function __construct(string $product, string $side, string $type, array $data)
    {
        $this->product = $product;
        $this->side = $side;
        $this->type = strtoupper($type);
        $this->data = $data;
    }

    /**
     * @return \App\Services\Platform\Resource\Order
     */
    public function handle(): OrderResource
    {
        return $this->resource($this->load($this->query()->data));
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestAuth('POST', '/api/v1/orders', [], $this->postData());
    }

    /**
     * @return array
     */
    protected function postData(): array
    {
        return array_filter([
            'clientOid' => microtime(true),
            'type' => $this->postDataType(),
            'side' => $this->side,
            'symbol' => $this->product,
            'stp' => 'CO',
            'tradeType' => 'TRADE',
        ] + $this->postDataByType());
    }

    /**
     * @return ?array
     */
    protected function postDataByType(): ?array
    {
        return match ($this->type) {
            'LIMIT' => $this->postDataLimit(),
            'MARKET' => $this->postDataMarket(),
            'STOP_LOSS' => $this->postDataLimit(),
            'STOP_LOSS_LIMIT' => $this->postDataLimit(),
            'TAKE_PROFIT' => $this->postDataLimit(),
            'TAKE_PROFIT_LIMIT' => $this->postDataLimit(),
            'LIMIT_MAKER' => $this->postDataLimit(),
        };
    }

    /**
     * @return string
     */
    protected function postDataType(): string
    {
        return match ($this->type) {
            'MARKET' => 'market',
            default => 'limit'
        };
    }

    /**
     * @return array
     */
    protected function postDataMarket(): array
    {
        return [
            'size' => $this->decimal($this->data['amount']),
        ];
    }

    /**
     * @return array
     */
    protected function postDataLimit(): array
    {
        return [
            'price' => $this->decimal($this->data['price']),
            'size' => $this->decimal($this->data['amount']),
            'timeInForce' => 'GTC',
            'stop' => $this->postDataLimitStop(),
            'stopPrice' => $this->postDataLimitStopPrice(),
        ];
    }

    /**
     * @return ?string
     */
    protected function postDataLimitStop(): ?string
    {
        return match ($this->type) {
            'STOP_LOSS', 'STOP_LOSS_LIMIT' => 'loss',
            'TAKE_PROFIT', 'TAKE_PROFIT_LIMIT' => 'entry',
            default => null
        };
    }

    /**
     * @return ?string
     */
    protected function postDataLimitStopPrice(): ?string
    {
        if (isset($this->data['limit'])) {
            return $this->decimal($this->data['limit']);
        }

        return null;
    }

    /**
     * @return \stdClass
     */
    protected function load(stdClass $row): stdClass
    {
        return $this->loadQuery($row->orderId)->data;
    }

    /**
     * @param string $id
     *
     * @return \stdClass
     */
    protected function loadQuery(string $id): stdClass
    {
        return $this->requestAuth('GET', '/api/v1/orders/'.$id);
    }
}
