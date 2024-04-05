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
     * @var float
     */
    public float $investment;

    /**
     * @param \Illuminate\Support\Collection $list
     * @param float $investment
     *
     * @return self
     */
    public function __construct(Collection $list, float $investment)
    {
        $this->list = $list;
        $this->investment = $investment;
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
        $buy_value = $this->list->sum('buy_value');
        $current_value = $this->list->sum('current_value');
        $sell_stop_min_value = $this->list->sum('sell_stop_min_value');

        return [
            'buy_value' => $buy_value,
            'current_value' => $current_value,
            'sell_stop_min_value' => $sell_stop_min_value,
            'result' => ($current_value - $this->investment),
            'result_percent' => helper()->percent($this->investment, $current_value),
        ];
    }
}
