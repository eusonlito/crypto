<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Scenario;

use App\Domains\Wallet\Model\Wallet as Model;

class Simulator
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $rowOriginal;

    /**
     * @var array
     */
    protected array $exchanges;

    /**
     * @var array
     */
    protected array $input;

    /**
     * @var float
     */
    protected float $exchange;

    /**
     * @var array
     */
    protected array $action = [
        'start' => [],
        'end' => [],
        'buy_stop' => [],
        'sell_stop' => [],
        'sell_stoploss' => [],
    ];

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param array $exchanges
     * @param array $input
     *
     * @return self
     */
    public function __construct(array $exchanges, array $input)
    {
        $this->input = $input;
        $this->exchanges = $exchanges;

        $this->row();
        $this->start();
        $this->iterate();
        $this->end();
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->rowCreate();
        $this->rowOriginal();
    }

    /**
     * @return void
     */
    protected function rowCreate(): void
    {
        $this->row = new Model([
            'id' => $this->input['id'],

            'amount' => $this->input['amount'],

            'buy_stop' => $this->input['buy_stop'],
            'buy_stop_amount' => $this->input['buy_stop_amount'],
            'buy_stop_min_percent' => $this->input['buy_stop_min_percent'],
            'buy_stop_max_percent' => $this->input['buy_stop_max_percent'],
            'buy_stop_max_follow' => $this->input['buy_stop_max_follow'],

            'sell_stop' => $this->input['sell_stop'],
            'sell_stop_amount' => $this->input['sell_stop_amount'],
            'sell_stop_max_percent' => $this->input['sell_stop_max_percent'],
            'sell_stop_min_percent' => $this->input['sell_stop_min_percent'],

            'sell_stoploss' => $this->input['sell_stoploss'],
            'sell_stoploss_percent' => $this->input['sell_stoploss_percent'],
        ]);

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
    protected function rowOriginal(): void
    {
        $this->rowOriginal = $this->row->replicate();
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $data = [
            'start_value' => $this->action['start'][0],
            'end_value' => $this->action['end'][0],

            'buy_stop_count' => count($this->action['buy_stop']),
            'buy_stop_value' => array_sum($this->action['buy_stop']),
            'buy_stop_max_percent' => $this->row->buy_stop_max_percent,
            'buy_stop_min_percent' => $this->row->buy_stop_min_percent,

            'sell_stop_count' => count($this->action['sell_stop']),
            'sell_stop_value' => array_sum($this->action['sell_stop']),
            'sell_stop_min_percent' => $this->row->sell_stop_min_percent,
            'sell_stop_max_percent' => $this->row->sell_stop_max_percent,

            'sell_stoploss_count' => count($this->action['sell_stoploss']),
            'sell_stoploss_value' => array_sum($this->action['sell_stoploss']),
            'sell_stoploss_percent' => $this->row->sell_stoploss_percent,
        ];

        $data['profit'] = $data['end_value']
            - $data['start_value']
            - $data['buy_stop_value']
            + $data['sell_stop_value']
            + $data['sell_stoploss_value'];

        $data['url'] = route('wallet.simulator')
            .'?id='.$this->row->id
            .'&'.http_build_query($this->input);

        return $data;
    }

    /**
     * @return void
     */
    protected function start(): void
    {
        $this->action('start', $this->row->buy_value);
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->exchanges as $exchange) {
            $this->exchange($exchange);
        }
    }

    /**
     * @return void
     */
    protected function end(): void
    {
        $this->action('end', $this->row->buy_value);
    }

    /**
     * @param float $exchange
     *
     * @return void
     */
    protected function exchange(float $exchange): void
    {
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

        $this->action('sell_stoploss', $this->exchange * $this->row->amount);

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

        $this->action('sell_stop', $this->exchange * $this->row->sell_stop_amount);

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

        $this->action('buy_stop', $this->exchange * $this->row->buy_stop_amount);

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
     * @param float $value
     *
     * @return void
     */
    protected function action(string $action, float $value): void
    {
        $this->action[$action][] = $value;
    }
}
