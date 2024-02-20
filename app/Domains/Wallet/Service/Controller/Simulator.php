<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Controller;

use stdClass;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as Model;

class Simulator
{
    /**
     * @var \stdClass
     */
    protected stdClass $row;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $orders;

    /**
     * @var string
     */
    protected string $datetime;

    /**
     * @var float
     */
    protected float $exchange;

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return self
     */
    public function __construct(Model $row, array $input)
    {
        $this->row($row, $input);
        $this->exchanges();
        $this->orders();
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return void
     */
    protected function row(Model $row, array $input): void
    {
        $this->row = $this->rowData($row, $input);

        $this->row->buy_stop_min_at = null;
        $this->row->buy_stop_max_at = null;

        $this->row->sell_stop_min_at = null;
        $this->row->sell_stop_max_at = null;

        $this->row->buy_market_at = null;

        $this->row->sell_stoploss_at = null;
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return \stdClass
     */
    protected function rowData(Model $row, array $input): stdClass
    {
        return json_decode(json_encode(array_map([$this, 'rowDataMap'], $input + [
            'id' => '',
            'address' => '',
            'name' => '',

            'amount' => 0,
            'buy_exchange' => 0,
            'current_exchange' => 0,
            'buy_value' => 0,
            'current_value' => 0,

            'sell_stop' => 0,
            'sell_stop_amount' => 0,
            'sell_stop_reference' => 0,
            'sell_stop_max_percent' => 0,
            'sell_stop_min_percent' => 0,
            'sell_stop_max_exchange' => 0,
            'sell_stop_min_exchange' => 0,
            'sell_stop_max_value' => 0,
            'sell_stop_min_value' => 0,

            'buy_stop' => 0,
            'buy_stop_amount' => 0,
            'buy_stop_reference' => 0,
            'buy_stop_max_follow' => 0,
            'buy_stop_min_percent' => 0,
            'buy_stop_min_exchange' => 0,
            'buy_stop_min_value' => 0,
            'buy_stop_max_percent' => 0,
            'buy_stop_max_exchange' => 0,
            'buy_stop_max_value' => 0,

            'buy_market' => 0,
            'buy_market_amount' => 0,
            'buy_market_reference' => 0,
            'buy_market_percent' => 0,
            'buy_market_exchange' => 0,
            'buy_market_value' => 0,

            'sell_stoploss' => 0,
            'sell_stoploss_percent' => 0,
            'sell_stoploss_exchange' => 0,
            'sell_stoploss_value' => 0,

            'exchange_reverse' => 0,
        ] + $row->toArray())));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function rowDataMap(mixed $value): mixed
    {
        if (is_string($value) === false) {
            return $value;
        }

        if (preg_match('/^[0-9]+$/', $value)) {
            return intval($value);
        }

        if (preg_match('/^[0-9]+\.[0-9]+$/', $value)) {
            return floatval($value);
        }

        return $value;
    }

    /**
     * @return void
     */
    protected function exchanges(): void
    {
        $this->exchanges = ExchangeModel::byProductId($this->row->product->id)
            ->pluck('exchange', 'created_at')
            ->when($this->row->exchange_reverse, static fn ($collection) => $collection->reverse());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getExchanges(): Collection
    {
        return $this->exchanges
            ->groupBy(fn ($value, $key) => $this->dateKey($key))
            ->map(fn ($value, $key) => $this->getExchangesMap($value, $key));
    }

    /**
     * @param \Illuminate\Support\Collection $values
     *
     * @return array
     */
    public function getExchangesMap(Collection $values, string $key): array
    {
        return [
            'datetime' => $key,
            'average' => round($average = $values->avg(), helper()->numberDecimals($average)),
        ];
    }

    /**
     * @return void
     */
    protected function orders(): void
    {
        $this->orders = collect();

        if (isset($this->row->_action) === false) {
            return;
        }

        foreach ($this->exchanges as $key => $value) {
            $this->exchange($key, $value);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @return \stdClass
     */
    public function getRow(): stdClass
    {
        return $this->row;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'exchanges' => ($exchanges = $this->getExchanges()),
            'exchangeFirst' => $exchanges->first()['average'] ?? 0,
            'exchangeLast' => $exchanges->last()['average'] ?? 0,
            'orders' => ($orders = $this->getOrders()),
            'ordersCompleted' => ($ordersCompleted = $orders->where('filled', true)),
            'ordersCompletedBuy' => ($ordersCompletedBuy = $ordersCompleted->where('side', 'buy')),
            'ordersCompletedBuyValue' => $ordersCompletedBuy->sum('value'),
            'ordersCompletedSell' => ($ordersCompletedSell = $ordersCompleted->where('side', 'sell')),
            'ordersCompletedSellValue' => $ordersCompletedSell->sum('value'),
            'profit' => $ordersCompleted->sum('profit'),
            'result' => $this->getRow(),
        ];
    }

    /**
     * @param string $datetime
     * @param float $exchange
     *
     * @return void
     */
    protected function exchange(string $datetime, float $exchange): void
    {
        $this->datetime = $datetime;
        $this->exchange = $exchange;

        $this->exchangeSellStopLoss();
        $this->exchangeSellStop();
        $this->exchangeBuyStop();
        $this->exchangeBuyMarket();
    }

    /**
     * @return void
     */
    protected function exchangeSellStopLoss(): void
    {
        if ($this->exchangeSellStopLossExecutable() === false) {
            return;
        }

        $amount = $this->row->amount;
        $profit = ($this->row->amount * $this->exchange) - ($this->row->amount * $this->row->buy_exchange);

        $this->row->amount = 0;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = 0;

        $this->row->sell_stoploss = false;

        if ($this->row->buy_stop_min_percent && $this->row->buy_stop_max_percent) {
            $this->row->buy_stop = true;
        }

        $this->row->buy_stop_reference = $this->row->buy_exchange;

        $this->row->buy_stop_min_exchange = $this->row->buy_stop_reference * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_max_exchange = $this->row->buy_stop_min_exchange * (1 + ($this->row->buy_stop_max_percent / 100));
        $this->row->buy_stop_max_at = null;

        $this->order('sell-stop-loss', $amount, true, $profit);
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopLossExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stoploss
            && $this->row->sell_stoploss_exchange
            && $this->row->sell_stoploss_percent
            && ($this->exchange <= $this->row->sell_stoploss_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeSellStop(): void
    {
        $this->exchangeSellStopMin();
        $this->exchangeSellStopMax();
    }

    /**
     * @return void
     */
    protected function exchangeSellStopMin(): void
    {
        if ($this->exchangeSellStopMinExecutable() === false) {
            return;
        }

        $this->row->sell_stop_amount = min($this->row->amount, $this->row->sell_stop_amount);

        $profit = ($this->row->sell_stop_amount * $this->exchange) - ($this->row->sell_stop_amount * $this->row->buy_exchange);

        $this->row->amount -= $this->row->sell_stop_amount;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = $this->row->buy_exchange * $this->row->amount;

        $this->row->sell_stop = false;
        $this->row->sell_stop_max_at = null;
        $this->row->sell_stop_min_at = null;

        if ($this->row->buy_stop_min_percent && $this->row->buy_stop_max_percent) {
            $this->row->buy_stop = true;
        }

        $this->row->buy_stop_reference = $this->row->buy_exchange;

        $this->row->buy_stop_min_exchange = $this->row->buy_stop_reference * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_max_exchange = $this->row->buy_stop_min_exchange * (1 + ($this->row->buy_stop_max_percent / 100));
        $this->row->buy_stop_max_at = null;

        if ($this->row->buy_market_percent) {
            $this->row->buy_market = true;
        }

        $this->row->buy_market_reference = $this->row->buy_exchange;

        $this->row->buy_market_exchange = $this->row->buy_market_reference * (1 + ($this->row->buy_market_percent / 100));
        $this->row->buy_market_at = null;

        $this->order('sell-stop-min', $this->row->sell_stop_amount, true, $profit);
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopMinExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_min_exchange
            && $this->row->sell_stop_max_exchange
            && $this->row->sell_stop_max_at
            && ($this->exchange <= $this->row->sell_stop_min_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeSellStopMax(): void
    {
        if ($this->exchangeSellStopMaxExecutable() === false) {
            return;
        }

        $this->row->sell_stop_max_exchange = $this->exchange;
        $this->row->sell_stop_max_at = $this->datetime;

        $this->row->sell_stop_min_exchange = $this->row->sell_stop_max_exchange * (1 - ($this->row->sell_stop_min_percent / 100));

        $profit = ($this->row->sell_stop_amount * $this->row->sell_stop_min_exchange) - ($this->row->sell_stop_amount * $this->row->buy_exchange);

        $this->order('sell-stop-max', $this->row->sell_stop_amount, false, $profit);
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopMaxExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_max_exchange
            && ($this->exchange >= $this->row->sell_stop_max_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStop(): void
    {
        $this->exchangeBuyStopFollow();
        $this->exchangeBuyStopMax();
        $this->exchangeBuyStopMin();
    }

    /**
     * @return void
     */
    protected function exchangeBuyStopFollow(): void
    {
        if ($this->exchangeBuyStopFollowExecutable() === false) {
            return;
        }

        $this->row->buy_stop_reference = $this->exchange;

        if ($this->row->buy_stop_min_exchange && $this->row->buy_stop_min_percent) {
            $this->row->buy_stop_min_exchange = $this->row->buy_stop_reference * (1 - ($this->row->buy_stop_min_percent / 100));
        }

        if ($this->row->buy_stop_max_exchange && $this->row->buy_stop_max_percent) {
            $this->row->buy_stop_max_exchange = $this->row->buy_stop_min_exchange * (1 + ($this->row->buy_stop_max_percent / 100));
        }
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopFollowExecutable(): bool
    {
        return $this->row->buy_stop
            and $this->row->buy_stop_reference
            and $this->row->buy_stop_max_follow
            and empty($this->row->buy_stop_min_at)
            and ($this->exchange >= $this->row->buy_stop_reference);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStopMax(): void
    {
        if ($this->exchangeBuyStopMaxExecutable() === false) {
            return;
        }

        $this->row->amount += $this->row->buy_stop_amount;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = $this->row->buy_exchange * $this->row->amount;

        $this->row->buy_stop = false;
        $this->row->buy_stop_min_at = null;
        $this->row->buy_stop_max_at = null;

        $this->row->buy_market = false;
        $this->row->buy_market_at = null;

        if ($this->row->sell_stop_max_percent && $this->row->sell_stop_min_percent) {
            $this->row->sell_stop = true;
            $this->row->sell_stop_amount = $this->row->sell_stop_amount ?: $this->row->buy_stop_amount;
        }

        $this->row->sell_stop_reference = $this->row->buy_exchange;

        $this->row->sell_stop_max_exchange = $this->row->sell_stop_reference * (1 + ($this->row->sell_stop_max_percent / 100));
        $this->row->sell_stop_max_at = null;

        $this->row->sell_stop_min_exchange = $this->row->sell_stop_max_exchange * (1 - ($this->row->sell_stop_min_percent / 100));
        $this->row->sell_stop_min_at = null;

        if ($this->row->sell_stoploss_percent) {
            $this->row->sell_stoploss = true;
        }

        $this->row->sell_stoploss_exchange = $this->row->buy_exchange * (1 - ($this->row->sell_stoploss_percent / 100));

        $this->order('buy-stop-max', $this->row->buy_stop_amount, true);
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMaxExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_max_exchange
            && $this->row->buy_stop_min_exchange
            && $this->row->buy_stop_min_at
            && ($this->exchange >= $this->row->buy_stop_max_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStopMin(): void
    {
        if ($this->exchangeBuyStopMinExecutable() === false) {
            return;
        }

        $this->row->buy_stop_min_exchange = $this->exchange;
        $this->row->buy_stop_min_at = $this->datetime;

        $this->row->buy_stop_max_exchange = $this->row->buy_stop_min_exchange * (1 + ($this->row->buy_stop_max_percent / 100));

        $this->order('buy-stop-min', $this->row->buy_stop_amount, false);
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMinExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_min_exchange
            && ($this->exchange <= $this->row->buy_stop_min_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeBuyMarket(): void
    {
        if ($this->exchangeBuyMarketExecutable() === false) {
            return;
        }

        $this->row->amount += $this->row->buy_market_amount;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = $this->row->amount * $this->row->buy_exchange;

        $this->row->buy_market = false;
        $this->row->buy_market_at = null;

        $this->row->buy_stop = false;
        $this->row->buy_stop_min_at = null;
        $this->row->buy_stop_max_at = null;

        if ($this->row->sell_stop_max_percent && $this->row->sell_stop_min_percent) {
            $this->row->sell_stop = true;
        }

        $this->row->sell_stop_reference = $this->row->buy_exchange;

        $this->row->sell_stop_max_exchange = $this->row->sell_stop_reference * (1 + ($this->row->sell_stop_max_percent / 100));
        $this->row->sell_stop_max_at = null;

        $this->row->sell_stop_min_exchange = $this->row->sell_stop_max_exchange * (1 - ($this->row->sell_stop_min_percent / 100));
        $this->row->sell_stop_min_at = null;

        if ($this->row->sell_stoploss_percent) {
            $this->row->sell_stoploss = true;
        }

        $this->row->sell_stoploss_exchange = $this->row->buy_exchange * (1 - ($this->row->sell_stoploss_percent / 100));

        $this->order('buy-market', $this->row->buy_market_amount, true);
    }

    /**
     * @return bool
     */
    protected function exchangeBuyMarketExecutable(): bool
    {
        return $this->row->buy_market
            && $this->row->buy_market_amount
            && $this->row->buy_market_exchange
            && empty($this->row->buy_stop_min_at)
            && empty($this->row->sell_stop_max_at)
            && ($this->exchange >= $this->row->buy_market_exchange);
    }

    /**
     * @param string $action
     * @param float $amount
     * @param bool $filled
     * @param float $profit = 0
     *
     * @return void
     */
    protected function order(string $action, float $amount, bool $filled, float $profit = 0): void
    {
        $this->orders->push((object)[
            'index' => $this->dateKey($this->datetime),
            'action' => $action,
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $amount,
            'value' => $this->exchange * $amount,
            'profit' => $profit,
            'filled' => $filled,

            'side' => explode('-', $action)[0],

            'wallet_buy_value' => $this->exchange * $this->row->amount,

            'wallet_buy_market_exchange' => $this->row->buy_market_exchange,

            'wallet_buy_stop_min_exchange' => $this->row->buy_stop_min_exchange,
            'wallet_buy_stop_max_exchange' => $this->row->buy_stop_max_exchange,

            'wallet_sell_stop_max_exchange' => $this->row->sell_stop_max_exchange,
            'wallet_sell_stop_min_exchange' => $this->row->sell_stop_min_exchange,

            'wallet_sell_stoploss_exchange' => $this->row->sell_stoploss_exchange,
        ]);
    }

    /**
     * @param string $datetime
     *
     * @return string
     */
    protected function dateKey(string $datetime): string
    {
        return date('Y-m-d H:i', (int)round(strtotime($datetime) / 300) * 300);
    }
}
