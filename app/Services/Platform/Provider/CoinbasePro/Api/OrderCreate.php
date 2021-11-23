<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use stdClass;
use App\Services\Platform\Resource\Order as OrderResource;

class OrderCreate extends ApiAbstract
{
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
        return $this->resource($this->query());
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestAuth('POST', '/orders', [], $this->queryData());
    }

    /**
     * @return array
     */
    protected function queryData(): array
    {
        return array_filter([
            'type' => $this->queryDataType(),
            'side' => $this->side,
            'product_id' => $this->product,
            'stp' => 'co',
        ] + $this->queryDataByType());
    }

    /**
     * @return ?array
     */
    protected function queryDataByType(): ?array
    {
        return match ($this->type) {
            'LIMIT' => $this->queryDataLimit(),
            'MARKET' => $this->queryDataMarket(),
            'STOP_LOSS' => $this->queryDataLimit(),
            'STOP_LOSS_LIMIT' => $this->queryDataLimit(),
            'TAKE_PROFIT' => $this->queryDataLimit(),
            'TAKE_PROFIT_LIMIT' => $this->queryDataLimit(),
            'LIMIT_MAKER' => $this->queryDataLimit(),
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
            'time_in_force' => 'GTC',
            'stop' => $this->queryDataLimitStop(),
            'stop_price' => $this->queryDataLimitStopPrice(),
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
     * @return ?float
     */
    protected function queryDataLimitStopPrice(): ?float
    {
        return $this->data['limit'] ?? null;
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Order
     */
    protected function resource(stdClass $row): OrderResource
    {
        return new OrderResource([
            'id' => $row->id,
            'amount' => (float)$row->size,
            'price' => (float)$row->price,
            'priceStop' => 0,
            'value' => ((float)$row->size * (float)$row->price),
            'fee' => (float)$row->fill_fees,
            'product' => $row->product_id,
            'status' => $row->status,
            'type' => $row->type,
            'side' => $row->side,
            'filled' => ($row->status === 'done'),
            'createdAt' => $this->date($row->created_at),
            'updatedAt' => $this->date($row->created_at),
        ]);
    }
}
