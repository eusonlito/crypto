<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class WalletStatGlobal extends Component
{
    /**
     * @var float
     */
    public float $investment;

    /**
     * @var float
     */
    public float $value;

    /**
     * @var float
     */
    public float $sellStopMinValue;

    /**
     * @param float $investment
     * @param float $value
     * @param float $sellStopMinValue
     *
     * @return self
     */
    public function __construct(Collection $list, float $investment, float $value, float $sellStopMinValue)
    {
        $this->investment = $investment;
        $this->value = $value;
        $this->sellStopMinValue = $sellStopMinValue;
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
        return [
            'current_value' => $this->value,
            'sell_stop_min_value' => $this->sellStopMinValue,
            'result' => ($this->value - $this->investment),
        ];
    }
}
