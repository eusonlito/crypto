<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Scenario;

use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Exceptions\UnexpectedValueException;

class Scenario
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @var array
     */
    protected array $exchanges;

    /**
     * @var array
     */
    protected array $input;

    /**
     * @var array
     */
    protected array $simulations = [];

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
        $this->row = $row;

        $this->input($input);
        $this->check();
        $this->exchanges();
        $this->iterate();
    }

    /**
     * @param array $input
     *
     * @return void
     */
    protected function input(array $input): void
    {
        $this->input = [
            'id' => $this->row->id,

            'time' => intval($input['time'] ?? 0),
            'amount' => floatval($input['amount'] ?? 0),
            'buy_exchange' => floatval($input['buy_exchange'] ?? 0),

            'sell_stop' => boolval($input['sell_stop'] ?? 0),
            'sell_stop_amount' => floatval($input['sell_stop_amount'] ?? 0),
            'sell_stop_max_percent_min' => floatval($input['sell_stop_max_percent_min'] ?? 0),
            'sell_stop_max_percent_max' => floatval($input['sell_stop_max_percent_max'] ?? 0),
            'sell_stop_min_percent_min' => floatval($input['sell_stop_min_percent_min'] ?? 0),
            'sell_stop_min_percent_max' => floatval($input['sell_stop_min_percent_max'] ?? 0),
            'sell_stop_percent_step' => floatval($input['sell_stop_percent_step'] ?? 0),

            'buy_stop' => boolval($input['buy_stop'] ?? 0),
            'buy_stop_amount' => floatval($input['buy_stop_amount'] ?? 0),
            'buy_stop_min_percent_min' => floatval($input['buy_stop_min_percent_min'] ?? 0),
            'buy_stop_min_percent_max' => floatval($input['buy_stop_min_percent_max'] ?? 0),
            'buy_stop_max_percent_min' => floatval($input['buy_stop_max_percent_min'] ?? 0),
            'buy_stop_max_percent_max' => floatval($input['buy_stop_max_percent_max'] ?? 0),
            'buy_stop_percent_step' => floatval($input['buy_stop_percent_step'] ?? 0),
            'buy_stop_max_follow' => boolval($input['buy_stop_max_follow'] ?? 0),

            'sell_stoploss' => boolval($input['sell_stoploss'] ?? 0),
            'sell_stoploss_percent_min' => floatval($input['sell_stoploss_percent_min'] ?? 0),
            'sell_stoploss_percent_max' => floatval($input['sell_stoploss_percent_max'] ?? 0),
            'sell_stoploss_percent_step' => floatval($input['sell_stoploss_percent_step'] ?? 0),

            'exchange_reverse' => boolval($input['exchange_reverse'] ?? 0),
            'exchange_first' => boolval($input['exchange_first'] ?? 0),
        ];
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        if (empty($this->input['time'])) {
            throw new UnexpectedValueException(__('wallet-scenario.error.time'));
        }

        if ($this->input['sell_stop_max_percent_min'] > $this->input['sell_stop_max_percent_max']) {
            throw new UnexpectedValueException(__('wallet-scenario.error.sell_stop_max_percent_min'));
        }

        if ($this->input['sell_stop_min_percent_min'] > $this->input['sell_stop_min_percent_max']) {
            throw new UnexpectedValueException(__('wallet-scenario.error.sell_stop_min_percent_min'));
        }

        if (empty($this->input['sell_stop_percent_step'])) {
            throw new UnexpectedValueException(__('wallet-scenario.error.sell_stop_percent_step'));
        }

        if ($this->input['buy_stop_min_percent_min'] > $this->input['buy_stop_min_percent_max']) {
            throw new UnexpectedValueException(__('wallet-scenario.error.buy_stop_min_percent_min'));
        }

        if ($this->input['buy_stop_max_percent_min'] > $this->input['buy_stop_max_percent_max']) {
            throw new UnexpectedValueException(__('wallet-scenario.error.buy_stop_max_percent_min'));
        }

        if (empty($this->input['buy_stop_percent_step'])) {
            throw new UnexpectedValueException(__('wallet-scenario.error.buy_stop_percent_step'));
        }

        if ($this->input['sell_stoploss_percent_min'] > $this->input['sell_stoploss_percent_max']) {
            throw new UnexpectedValueException(__('wallet-scenario.error.sell_stoploss_percent_min'));
        }

        if (empty($this->input['sell_stoploss_percent_step'])) {
            throw new UnexpectedValueException(__('wallet-scenario.error.sell_stoploss_percent_step'));
        }
    }

    /**
     * @return void
     */
    protected function exchanges(): void
    {
        $this->exchanges = ExchangeModel::query()
            ->byProductId($this->row->product->id)
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
    protected function iterate(): void
    {
        set_time_limit(0);

        $this->iterateSimulator();
        $this->iterateSort();
    }

    /**
     * @return void
     */
    protected function iterateSimulator(): void
    {
        $this->input['buy_stop_min_percent'] = $this->input['buy_stop_min_percent_min'];

        while ($this->input['buy_stop_min_percent'] <= $this->input['buy_stop_min_percent_max']) {
            $this->input['buy_stop_max_percent'] = $this->input['buy_stop_max_percent_min'];

            while ($this->input['buy_stop_max_percent'] <= $this->input['buy_stop_max_percent_max']) {
                $this->input['sell_stop_max_percent'] = $this->input['sell_stop_max_percent_min'];

                while ($this->input['sell_stop_max_percent'] <= $this->input['sell_stop_max_percent_max']) {
                    $this->input['sell_stop_min_percent'] = $this->input['sell_stop_min_percent_min'];

                    while ($this->input['sell_stop_min_percent'] <= $this->input['sell_stop_min_percent_max']) {
                        $this->input['sell_stoploss_percent'] = $this->input['sell_stoploss_percent_min'];

                        while ($this->input['sell_stoploss_percent'] <= $this->input['sell_stoploss_percent_max']) {
                            $this->simulator();

                            $this->input['sell_stoploss_percent'] += $this->input['sell_stoploss_percent_step'];
                        }

                        $this->input['sell_stop_min_percent'] += $this->input['sell_stop_percent_step'];
                    }

                    $this->input['sell_stop_max_percent'] += $this->input['sell_stop_percent_step'];
                }

                $this->input['buy_stop_max_percent'] += $this->input['buy_stop_percent_step'];
            }

            $this->input['buy_stop_min_percent'] += $this->input['buy_stop_percent_step'];
        }
    }

    /**
     * @return void
     */
    protected function simulator(): void
    {
        $this->simulations[] = Simulator::new($this->exchanges, $this->input)->get();
    }

    /**
     * @return void
     */
    protected function iterateSort(): void
    {
        uasort($this->simulations, static fn ($a, $b) => $b['profit'] <=> $a['profit']);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'exchanges' => $this->exchanges,
            'simulations' => $this->simulations,
        ];
    }
}
