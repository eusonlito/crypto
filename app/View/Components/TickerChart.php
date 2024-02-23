<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Ticker\Model\Ticker as Model;

class TickerChart extends Component
{
    /**
     * @var \App\Domains\Ticker\Model\Ticker
     */
    public Model $row;

    /**
     * @var bool
     */
    public bool $references;

    /**
     * @param \App\Domains\Ticker\Model\Ticker $row
     * @param bool $references = true
     *
     * @return self
     */
    public function __construct(Model $row, bool $references = true)
    {
        $this->row = $row;
        $this->references = $references;
    }

    /**
     * @return bool
     */
    public function shouldRender(): bool
    {
        return boolval($this->row->exchanges->last());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.ticker.modules.chart', $this->renderData());
    }

    /**
     * @return array
     */
    protected function renderData(): array
    {
        return [
            'row' => $this->row,
            'product' => $this->row->product,
            'references' => $this->row->references,
            'exchanges' => $this->row->exchanges,
        ];
    }
}
