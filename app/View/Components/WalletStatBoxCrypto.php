<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Wallet\Model\Wallet as Model;

class WalletStatBoxCrypto extends Component
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    public Model $row;

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return self
     */
    public function __construct(Model $row)
    {
        $this->row = $row;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.wallet.modules.stat-box-crypto', $this->renderData());
    }

    /**
     * @return array
     */
    protected function renderData(): array
    {
        return $this->row->attributesToArray() + [
            'current_exchange_percent' => helper()->percent($this->row->buy_exchange, $this->row->current_exchange),
            'current_value_percent' => helper()->percent($this->row->buy_value, $this->row->current_value),

            'buy_stop_min_exchange_percent' => helper()->percent($this->row->current_exchange, $this->row->buy_stop_min_exchange),
            'buy_stop_min_value_percent' => helper()->percent($this->row->current_value, $this->row->buy_stop_min_value),
            'buy_stop_min_value_difference' => ($this->row->buy_stop_min_value - ($this->row->buy_stop_amount * $this->row->buy_exchange)),
            'buy_stop_max_exchange_percent' => helper()->percent($this->row->current_exchange, $this->row->buy_stop_max_exchange),
            'buy_stop_max_value_percent' => helper()->percent($this->row->current_value, $this->row->buy_stop_max_value),
            'buy_stop_max_value_difference' => ($this->row->buy_stop_max_value - ($this->row->buy_stop_amount * $this->row->buy_exchange)),

            'sell_stop_min_exchange_percent' => helper()->percent($this->row->sell_stop_min_exchange, $this->row->current_exchange),
            'sell_stop_min_value_percent' => helper()->percent($this->row->sell_stop_min_value, $this->row->current_value),
            'sell_stop_min_value_difference' => ($this->row->sell_stop_min_value - ($this->row->sell_stop_amount * $this->row->buy_exchange)),
            'sell_stop_max_exchange_percent' => helper()->percent($this->row->sell_stop_max_exchange, $this->row->current_exchange),
            'sell_stop_max_value_percent' => helper()->percent($this->row->sell_stop_max_value, $this->row->current_value),
            'sell_stop_max_value_difference' => ($this->row->sell_stop_max_value - ($this->row->sell_stop_amount * $this->row->buy_exchange)),

            'sell_stoploss_exchange_percent' => helper()->percent($this->row->sell_stoploss_exchange, $this->row->current_exchange),
            'sell_stoploss_value_percent' => helper()->percent($this->row->sell_stoploss_value, $this->row->current_value),

            'result' => ($this->row->current_value - $this->row->buy_value),
        ];
    }
}
