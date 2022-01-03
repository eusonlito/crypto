<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Percent;

use stdClass;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as Model;

class Calculator
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
     * @var array
     */
    protected array $orders;

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
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $input
     *
     * @return void
     */
    protected function row(Model $row, array $input): void
    {
        $this->row = json_decode(json_encode(array_map([$this, 'rowMap'], $input) + $row->toArray()));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    protected function rowMap(mixed $value): mixed
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
            ->afterDate(date('Y-m-d H:i:s', strtotime('-15 days')))
            ->pluck('exchange', 'created_at');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getExchanges(): Collection
    {
        return $this->exchanges
            ->groupBy(static fn ($value, $key) => explode(':', $key)[0])
            ->map(static fn ($value) => round($avg = $value->avg(), helper()->numberDecimals($avg)));
    }

    /**
     * @return array
     */
    public function getOrders(): array
    {
        if (isset($this->row->_action) === false) {
            return [];
        }

        $this->orders = [];

        foreach ($this->exchanges as $key => $value) {
            $this->exchange($key, $value);
        }

        return $this->orders;
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
    }

    /**
     * @return void
     */
    protected function exchangeSellStopLoss(): void
    {
        if ($this->exchangeSellStopLossExecutable() === false) {
            return;
        }

        $this->row->amount = 0;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = 0;

        $this->row->sell_stoploss = false;

        if ($this->row->buy_stop_min_percent && $this->row->buy_stop_percent) {
            $this->row->buy_stop = true;
        }

        $this->row->buy_stop_min = $this->row->buy_exchange * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_max = $this->row->buy_stop_min * (1 + ($this->row->buy_stop_percent / 100));
        $this->row->buy_stop_max_at = null;

        $this->orders[] = (object)[
            'action' => 'sell-stop-loss',
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $this->row->amount,
            'value' => $this->exchange * $this->row->amount,
            'filled' => true,
            'row' => clone $this->row,
        ];
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopLossExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stoploss
            && $this->row->sell_stoploss_percent
            && ($this->exchange <= ($this->row->buy_exchange * (1 - ($this->row->sell_stoploss_percent / 100))));
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

        $this->row->amount -= $this->row->sell_stop_amount;
        $this->row->buy_exchange = $this->exchange;
        $this->row->buy_value = $this->row->buy_exchange * $this->row->amount;

        $this->row->sell_stop = false;
        $this->row->sell_stop_max_at = null;
        $this->row->sell_stop_min_at = null;

        if ($this->row->buy_stop_min_percent && $this->row->buy_stop_percent) {
            $this->row->buy_stop = true;
        }

        $this->row->buy_stop_min = $this->row->buy_exchange * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_max = $this->row->buy_stop_min * (1 + ($this->row->buy_stop_percent / 100));
        $this->row->buy_stop_max_at = null;

        $this->orders[] = (object)[
            'action' => 'sell-stop-min',
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $this->row->sell_stop_amount,
            'value' => $this->exchange * $this->row->sell_stop_amount,
            'filled' => true,
            'row' => clone $this->row,
        ];
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopMinExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_min
            && $this->row->sell_stop_max
            && $this->row->sell_stop_max_at
            && ($this->exchange <= $this->row->sell_stop_min);
    }

    /**
     * @return void
     */
    protected function exchangeSellStopMax(): void
    {
        if ($this->exchangeSellStopMaxExecutable() === false) {
            return;
        }

        $this->row->sell_stop_max = $this->exchange;
        $this->row->sell_stop_max_at = $this->datetime;

        $this->row->sell_stop_min = $this->row->sell_stop_max * (1 - ($this->row->sell_stop_percent / 100));

        $this->orders[] = (object)[
            'action' => 'sell-stop-max',
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $this->row->sell_stop_amount,
            'value' => $this->exchange * $this->row->sell_stop_amount,
            'filled' => false,
            'row' => clone $this->row,
        ];
    }

    /**
     * @return bool
     */
    protected function exchangeSellStopMaxExecutable(): bool
    {
        return $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_max
            && ($this->exchange >= $this->row->sell_stop_max);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStop(): void
    {
        $this->exchangeBuyStopMax();
        $this->exchangeBuyStopMin();
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

        if ($this->row->sell_stop_max_percent && $this->row->sell_stop_percent) {
            $this->row->sell_stop = true;
        }

        $this->row->sell_stop_max = $this->row->buy_exchange * (1 + ($this->row->sell_stop_max_percent / 100));
        $this->row->sell_stop_max_at = null;

        $this->row->sell_stop_min = $this->row->sell_stop_max * (1 - ($this->row->sell_stop_percent / 100));
        $this->row->sell_stop_min_at = null;

        $this->orders[] = (object)[
            'action' => 'buy-stop-max',
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $this->row->buy_stop_amount,
            'value' => $this->exchange * $this->row->buy_stop_amount,
            'filled' => true,
            'row' => clone $this->row,
        ];
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMaxExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_max
            && $this->row->buy_stop_min
            && $this->row->buy_stop_min_at
            && ($this->exchange >= $this->row->buy_stop_max);
    }

    /**
     * @return void
     */
    protected function exchangeBuyStopMin(): void
    {
        if ($this->exchangeBuyStopMinExecutable() === false) {
            return;
        }

        $this->row->buy_stop_min = $this->exchange;
        $this->row->buy_stop_min_at = $this->datetime;

        $this->row->buy_stop_max = $this->row->buy_stop_min * (1 + ($this->row->buy_stop_percent / 100));

        $this->orders[] = (object)[
            'action' => 'buy-stop-min',
            'created_at' => $this->datetime,
            'exchange' => $this->exchange,
            'amount' => $this->row->buy_stop_amount,
            'value' => $this->exchange * $this->row->buy_stop_amount,
            'filled' => false,
            'row' => clone $this->row,
        ];
    }

    /**
     * @return bool
     */
    protected function exchangeBuyStopMinExecutable(): bool
    {
        return $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_min
            && ($this->exchange <= $this->row->buy_stop_min);
    }
}


