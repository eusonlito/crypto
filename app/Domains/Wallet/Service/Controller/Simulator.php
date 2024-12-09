<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Controller;

use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as Model;

class Simulator
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @var array
     */
    protected array $input;

    /**
     * @var array
     */
    protected array $exchanges;

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
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return self
     */
    public function __construct(Model $row, array $input)
    {
        $this->input($row, $input);
        $this->exchanges($row);
        $this->row();
        $this->orders();
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return void
     */
    protected function input(Model $row, array $input): void
    {
        $this->input = $row->toArray();

        foreach ($input as $key => $value) {
            if (is_string($value) === false) {
                $this->input[$key] = $value;
            } elseif (preg_match('/^[0-9]+$/', $value)) {
                $this->input[$key] = intval($value);
            } elseif (preg_match('/^[0-9]+\.[0-9]+$/', $value)) {
                $this->input[$key] = floatval($value);
            } else {
                $this->input[$key] = $value;
            }
        }

        $this->input['buy_stop_min_exchange'] = $this->input['buy_exchange'] * (1 - ($this->input['buy_stop_min_percent'] / 100));
        $this->input['buy_stop_max_exchange'] = $this->input['buy_stop_min_exchange'] * (1 + ($this->input['buy_stop_max_percent'] / 100));

        $this->input['buy_stop_amount'] = $this->input['buy_stop_max_value'] / $this->input['buy_stop_max_exchange'];
        $this->input['buy_stop_min_value'] = $this->input['buy_stop_amount'] * $this->input['buy_stop_min_exchange'];

        $this->input['sell_stop_max_exchange'] = $this->input['buy_exchange'] * (1 + ($this->input['sell_stop_max_percent'] / 100));
        $this->input['sell_stop_min_exchange'] = $this->input['sell_stop_max_exchange'] * (1 - ($this->input['sell_stop_min_percent'] / 100));

        $this->input['sell_stop_max_value'] = $this->input['sell_stop_amount'] * $this->input['sell_stop_max_exchange'];
        $this->input['sell_stop_min_value'] = $this->input['sell_stop_amount'] * $this->input['sell_stop_min_exchange'];

        ksort($this->input);

        $this->input = array_filter(
            $this->input,
            static fn ($value) => (is_object($value) === false) && (is_array($value) === false)
        );
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return void
     */
    protected function exchanges(Model $row): void
    {
        $this->exchanges = ExchangeModel::query()
            ->byProductId($row->product->id)
            ->afterMinutes($this->input['time'])
            ->pluck('exchange', 'created_at')
            ->all();

        if ($this->input['exchange_reverse']) {
            krsort($this->exchanges);
        } else {
            ksort($this->exchanges);
        }
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = new Model($this->input);

        if ($this->input['exchange_first']) {
            $this->row->updateBuy(reset($this->exchanges) ?: 0);
        } else {
            $this->row->updateBuy($this->input['buy_exchange']);
        }

        if ($this->row->buy_stop) {
            $this->row->updateBuyStopEnable();
        } else {
            $this->row->updateBuyStopDisable();
        }

        if ($this->row->sell_stop) {
            $this->row->updateSellStopEnable();
        } else {
            $this->row->updateSellStopDisable();
        }

        if ($this->row->sell_stoploss) {
            $this->row->updateSellStopLossEnable();
        } else {
            $this->row->updateSellStopLossDisable();
        }
    }

    /**
     * @return void
     */
    protected function orders(): void
    {
        $this->orders = collect();

        if (isset($this->input['_action']) === false) {
            return;
        }

        $this->datetime = date('Y-m-d H:i:s', strtotime(array_key_first($this->exchanges).' -1 second'));
        $this->exchange = $this->row->buy_exchange;

        $this->order('start', $this->row->amount);

        foreach ($this->exchanges as $key => $value) {
            $this->exchange($key, $value);
        }

        $this->datetime = date('Y-m-d H:i:s', strtotime(array_key_last($this->exchanges).' +1 second'));
        $this->exchange = $this->row->buy_exchange;

        $this->order('end', $this->row->amount);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'exchanges' => $this->exchanges,
            'exchangeFirst' => reset($this->exchanges),
            'exchangeLast' => end($this->exchanges),
            'orders' => $this->orders->whereNotIn('side', ['start', 'end']),
            'ordersBuy' => $this->orders->where('side', 'buy'),
            'ordersBuyValue' => $this->orders->where('side', 'buy')->sum('value'),
            'ordersSell' => $this->orders->where('side', 'sell'),
            'ordersSellValue' => $this->orders->where('side', 'sell')->sum('value'),
            'profit' => $this->dataProfit(),
            'rowResult' => $this->row,
        ];
    }

    /**
     * @return float
     */
    protected function dataProfit(): float
    {
        return $this->orders->where('side', 'end')->sum('value')
            - $this->orders->where('side', 'start')->sum('value')
            - $this->orders->where('side', 'buy')->sum('value')
            + $this->orders->where('side', 'sell')->sum('value');
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

        $this->row->updateBuy($exchange);
    }

    /**
     * @return void
     */
    protected function exchangeSellStopLoss(): void
    {
        if ($this->exchangeSellStopLossExecutable() === false) {
            return;
        }

        $this->order('sell_stoploss', $this->row->amount);

        $this->row->amount = 0;

        $this->row->updateBuy($this->exchange);
        $this->row->updateBuyStopEnable();
        $this->row->updateSellStopDisable();
        $this->row->updateSellStopLossDisable();
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
        $this->exchangeSellStopMax();
        $this->exchangeSellStopMin();
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
        $this->row->sell_stop_max_at = date('Y-m-d H:i:s');

        $this->row->sell_stop_min_exchange = $this->row->sell_stop_max_exchange * (1 - ($this->row->sell_stop_min_percent / 100));
        $this->row->sell_stop_min_value = $this->row->sell_stop_amount * $this->row->sell_stop_min_exchange;
        $this->row->sell_stop_min_at = null;
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
            && $this->row->sell_stop_min_percent
            && ($this->exchange >= $this->row->sell_stop_max_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeSellStopMin(): void
    {
        if ($this->exchangeSellStopMinExecutable() === false) {
            return;
        }

        $this->order('sell_stop', $this->row->sell_stop_amount);

        $this->row->amount -= $this->row->sell_stop_amount;
        $this->row->amount = max($this->row->amount, 0);

        $this->row->updateBuy($this->exchange);
        $this->row->updateBuyStopEnable();
        $this->row->updateSellStopDisable();
        $this->row->updateSellStopLossDisable();
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
            && $this->row->sell_stop_min_percent
            && $this->row->sell_stop_max_exchange
            && $this->row->sell_stop_max_percent
            && $this->row->sell_stop_max_at
            && ($this->exchange <= $this->row->sell_stop_min_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStop(): void
    {
        $this->exchangeBuyStopFollow();
        $this->exchangeBuyStopMin();
        $this->exchangeBuyStopMax();
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
            && $this->row->buy_stop_reference
            && $this->row->buy_stop_max_follow
            && empty($this->row->buy_stop_min_at)
            && ($this->exchange >= $this->row->buy_stop_reference);
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
        $this->row->buy_stop_min_at = date('Y-m-d H:i:s');

        $this->row->buy_stop_max_exchange = $this->row->buy_stop_min_exchange * (1 + ($this->row->buy_stop_max_percent / 100));
        $this->row->buy_stop_max_value = $this->row->buy_stop_amount * $this->row->buy_stop_max_exchange;
        $this->row->buy_stop_max_at = null;
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMinExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_min_exchange
            && $this->row->buy_stop_max_percent
            && ($this->exchange <= $this->row->buy_stop_min_exchange);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStopMax(): void
    {
        if ($this->exchangeBuyStopMaxExecutable() === false) {
            return;
        }

        $this->order('buy_stop', $this->row->buy_stop_amount);

        $this->row->amount += $this->row->buy_stop_amount;

        $this->row->updateBuy($this->exchange);
        $this->row->updateBuyStopDisable();
        $this->row->updateSellStopEnable();
        $this->row->updateSellStopLossEnable();
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMaxExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_max_exchange
            && $this->row->buy_stop_max_percent
            && $this->row->buy_stop_min_exchange
            && $this->row->buy_stop_min_percent
            && $this->row->buy_stop_min_at
            && ($this->exchange >= $this->row->buy_stop_max_exchange);
    }

    /**
     * @param string $action
     * @param float $amount
     *
     * @return void
     */
    protected function order(string $action, float $amount): void
    {
        $value = $amount * $this->exchange;

        if (in_array($action, ['sell_stop', 'sell_stoploss'])) {
            $profit = $value - ($this->orders->last()->value ?? 0);
        } else {
            $profit = 0;
        }

        $this->orders->push((object)[
            'action' => $action,
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $amount,
            'value' => $value,
            'profit' => $profit,

            'side' => explode('_', $action)[0],

            'wallet_buy_value' => $this->exchange * $this->row->amount,

            'wallet_buy_stop_min_exchange' => $this->row->buy_stop_min_exchange,
            'wallet_buy_stop_max_exchange' => $this->row->buy_stop_max_exchange,

            'wallet_sell_stop_max_exchange' => $this->row->sell_stop_max_exchange,
            'wallet_sell_stop_min_exchange' => $this->row->sell_stop_min_exchange,

            'wallet_sell_stoploss_exchange' => $this->row->sell_stoploss_exchange,
        ]);
    }
}
