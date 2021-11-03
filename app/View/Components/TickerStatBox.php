<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Ticker\Model\Ticker as Model;

class TickerStatBox extends Component
{
    /**
     * @var \App\Domains\Ticker\Model\Ticker
     */
    public Model $row;

    /**
     * @param \App\Domains\Ticker\Model\Ticker $row
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
        return view('domains.ticker.modules.stat-box', $this->renderData());
    }

    /**
     * @return array
     */
    protected function renderData(): array
    {
        return [
            'currency' => $this->row->currency,
            'exchanges' => $this->row->exchanges,

            'amount' => $this->row->amount,

            'exchange_reference' => $this->row->exchange_reference,
            'exchange_current' => $this->row->exchange_current,
            'exchange_current_percent' => helper()->percent($this->row->exchange_reference, $this->row->exchange_current),
            'exchange_min' => $this->row->exchange_min,
            'exchange_min_percent' => helper()->percent($this->row->exchange_reference, $this->row->exchange_min),
            'exchange_min_at' => $this->row->exchange_min_at,
            'exchange_max' => $this->row->exchange_max,
            'exchange_max_percent' => helper()->percent($this->row->exchange_reference, $this->row->exchange_max),
            'exchange_max_at' => $this->row->exchange_max_at,

            'value_reference' => $this->row->value_reference,
            'value_current' => $this->row->value_current,
            'value_current_percent' => helper()->percent($this->row->value_reference, $this->row->value_current),
            'value_min' => $this->row->value_min,
            'value_min_percent' => helper()->percent($this->row->value_reference, $this->row->value_min),
            'value_max' => $this->row->value_max,
            'value_max_percent' => helper()->percent($this->row->value_reference, $this->row->value_max),

            'result_current' => ($this->row->value_current - $this->row->value_reference),
            'result_min' => ($this->row->value_min - $this->row->value_reference),
            'result_max' => ($this->row->value_max - $this->row->value_reference),
        ];
    }
}
