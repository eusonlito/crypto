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
     * @var int
     */
    public int $investment;

    /**
     * @param \Illuminate\Support\Collection $list
     * @param int $investment
     *
     * @return self
     */
    public function __construct(Collection $list, int $investment)
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
        $current_value = $this->list->sum('current_value');

        return [
            'current_value' => $current_value,
            'sell_stop_min_value' => $this->list->sum('sell_stop_min_value'),
            'result' => ($current_value - $this->investment),
        ];
    }
}
