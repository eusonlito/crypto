<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Throwable;
use App\Services\Platform\Provider\Binance\Api\Traits\OrderResource as OrderResourceTrait;
use App\Services\Platform\Resource\Order as OrderResource;

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
        return $this->resource($this->query());
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        try {
            return $this->requestAuth('POST', '/api/v3/order', $this->queryData());
        } catch (Throwable $e) {
            $this->exception($e);
        }
    }

    /**
     * @return array
     */
    protected function queryData(): array
    {
        return array_filter([
            'symbol' => $this->product,
            'side' => $this->side,
            'type' => $this->type,
            'newOrderRespType' => 'FULL',
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
            'STOP_LOSS' => $this->queryDataStopLoss(),
            'STOP_LOSS_LIMIT' => $this->queryDataStopLossLimit(),
            'TAKE_PROFIT' => $this->queryDataTakeProfit(),
            'TAKE_PROFIT_LIMIT' => $this->queryDataTakeProfitLimit(),
            'LIMIT_MAKER' => $this->queryDataLimitMaker(),
        };
    }

    /**
     * @return array
     */
    protected function queryDataLimit(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'price' => $this->decimal($this->data['price']),
            'timeInForce' => 'GTC',
        ];
    }

    /**
     * @return array
     */
    protected function queryDataMarket(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
        ];
    }

    /**
     * @return array
     */
    protected function queryDataStopLoss(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'stopPrice' => $this->decimal($this->data['price']),
        ];
    }

    /**
     * @return array
     */
    protected function queryDataStopLossLimit(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'stopPrice' => $this->decimal($this->data['limit']),
            'price' => $this->decimal($this->data['price']),
            'timeInForce' => 'GTC',
        ];
    }

    /**
     * @return array
     */
    protected function queryDataTakeProfit(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'stopPrice' => $this->decimal($this->data['price']),
        ];
    }

    /**
     * @return array
     */
    protected function queryDataTakeProfitLimit(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'stopPrice' => $this->decimal($this->data['limit']),
            'price' => $this->decimal($this->data['price']),
            'timeInForce' => 'GTC',
        ];
    }

    /**
     * @return array
     */
    protected function queryDataLimitMaker(): array
    {
        return [
            'quantity' => $this->decimal($this->data['amount']),
            'price' => $this->decimal($this->data['price']),
        ];
    }
}
