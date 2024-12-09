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
        $this->input = $this->row->toArray();

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

        if ($this->input['exchange_reverse'] ?? false) {
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
