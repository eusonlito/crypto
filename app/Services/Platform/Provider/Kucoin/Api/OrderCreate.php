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
        return $this->requestAuth('POST', '/api/v1/orders', [], $this->queryData());
    }

    /**
     * @return array
     */
    protected function queryData(): array
    {
        return array_filter([
            'clientOid' => microtime(true),
            'type' => $this->queryDataType(),
            'side' => $this->side,
            'symbol' => $this->product,
            'stp' => 'CO',
            'tradeType' => 'TRADE',
        ] + $this->queryDataByType());
    }

    /**
     * @return array
     */
    protected function queryDataByType(): array
    {
        return match ($this->type) {
            'LIMIT' => $this->queryDataLimit(),
            'MARKET' => $this->queryDataMarket(),
            'STOP_LOSS' => $this->queryDataLimit(),
            'STOP_LOSS_LIMIT' => $this->queryDataLimit(),
            'TAKE_PROFIT' => $this->queryDataLimit(),
            'TAKE_PROFIT_LIMIT' => $this->queryDataLimit(),
            'LIMIT_MAKER' => $this->queryDataLimit(),
            default => [],
        };
    }

    /**
     * @return string
     */
    protected function queryDataType(): string
    {
        return match ($this->type) {
            'MARKET' => 'market',
            default => 'limit'
        };
    }

    /**
     * @return array
     */
    protected function queryDataMarket(): array
    {
        return [
            'size' => $this->decimal($this->data['amount']),
        ];
    }

    /**
     * @return array
     */
    protected function queryDataLimit(): array
    {
        return [
            'price' => $this->decimal($this->data['price']),
            'size' => $this->decimal($this->data['amount']),
            'timeInForce' => 'GTC',
            'stop' => $this->queryDataLimitStop(),
            'stopPrice' => $this->queryDataLimitStopPrice(),
        ];
    }

    /**
     * @return ?string
     */
    protected function queryDataLimitStop(): ?string
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
    protected function queryDataLimitStopPrice(): ?string
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
