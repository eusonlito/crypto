<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
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
     * @var ?string
     */
    protected ?string $reference;

    /**
     * @var bool
     */
    protected bool $log = true;

    /**
     * @param string $product
     * @param string $side
     * @param string $type
     * @param array $data
     * @param ?string $reference = null
     *
     * @return self
     */
    public function __construct(string $product, string $side, string $type, array $data, ?string $reference = null)
    {
        $this->product = $product;
        $this->side = $side;
        $this->type = strtoupper($type);
        $this->data = $data;
        $this->reference = $reference;
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
        return app()->isProduction()
            ? $this->queryProduction()
            : $this->queryFake();
    }

    /**
     * @return \stdClass
     */
    protected function queryProduction(): stdClass
    {
        return $this->requestAuth('POST', '/api/v3/order', $this->queryData());
    }

    /**
     * @return \stdClass
     */
    protected function queryFake(): stdClass
    {
        return (object)[
            'symbol' => $this->product,
            'orderId' => rand(100000, 999999),
            'clientOrderId' => $this->reference,
            'transactTime' => time() * 1000,
            'price' => $this->queryFakePrice(),
            'origQty' => $this->decimal($this->data['amount'] ?? 0),
            'executedQty' => 0,
            'cummulativeQuoteQty' => 0,
            'status' => 'NEW',
            'timeInForce' => 'GTC',
            'type' => $this->type,
            'side' => $this->side,
        ];
    }

    /**
     * @return float
     */
    protected function queryFakePrice(): float
    {
        return $this->data['price']
            ?? $this->queryFakePriceLast();
    }

    /**
     * @return float
     */
    protected function queryFakePriceLast(): float
    {
        return ExchangeModel::query()
            ->byProductCode($this->product)
            ->orderByLast()
            ->value('exchange');
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
            'newClientOrderId' => $this->reference,
            'newOrderRespType' => 'FULL',
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
            'STOP_LOSS' => $this->queryDataStopLoss(),
            'STOP_LOSS_LIMIT' => $this->queryDataStopLossLimit(),
            'TAKE_PROFIT' => $this->queryDataTakeProfit(),
            'TAKE_PROFIT_LIMIT' => $this->queryDataTakeProfitLimit(),
            'LIMIT_MAKER' => $this->queryDataLimitMaker(),
            default => [],
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
            'stopPrice' => $this->decimal($this->data['price']),
            'price' => $this->decimal($this->data['limit']),
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
            'stopPrice' => $this->decimal($this->data['price']),
            'price' => $this->decimal($this->data['limit']),
            'trailingDelta' => ($this->data['trailing'] ?? 0),
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
