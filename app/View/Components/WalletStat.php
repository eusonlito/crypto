<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Wallet\Model\Wallet as Model;

class WalletStat extends Component
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
        return view('domains.wallet.modules.stat', $this->renderData());
    }

    /**
     * @return array
     */
    protected function renderData(): array
    {
        return [
            'currency' => $this->row->currency,
            'exchanges' => $this->row->exchanges,
            'current_exchange' => $this->row->current_exchange,
            'buy_exchange' => $this->row->buy_exchange,
            'sell_stop_min' => $this->row->sell_stop_min,
            'amount' => $this->row->amount,
            'buy_value' => $this->row->buy_value,
            'current_value' => $this->row->current_value,
            'sell_stop_min_value' => $this->row->sell_stop_min_value,
            'result' => ($this->row->current_value - $this->row->buy_value),
        ];
    }
}
