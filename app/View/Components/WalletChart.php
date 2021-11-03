<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Wallet\Model\Wallet as Model;

class WalletChart extends Component
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    public Model $row;

    /**
     * @var bool
     */
    public bool $references;

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
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
     * @return ?\Illuminate\View\View
     */
    public function render(): ?View
    {
        if ($this->row->exchanges->last() === null) {
            return null;
        }

        return view('domains.wallet.modules.chart', $this->renderData());
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
