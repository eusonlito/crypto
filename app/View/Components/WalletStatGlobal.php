<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class WalletStatGlobal extends Component
{
    /**
     * @var \Illuminate\Support\Collection
     */
    public Collection $list;

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return self
     */
    public function __construct(Collection $list)
    {
        $this->list = $list;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.wallet.modules.stat-global', $this->renderData());
    }

    /**
     * @return array
     */
    protected function renderData(): array
    {
        $buy_value = $this->list->where('ticker', false)->sum('buy_value');
        $current_value = $this->list->where('ticker', false)->sum('current_value');

        return [
            'buy_value' => $buy_value,
            'current_value' => $current_value,
            'sell_stop_min_value' => $this->list->where('ticker', false)->sum('sell_stop_min_value'),
            'result' => ($current_value - $buy_value),
        ];
    }
}
