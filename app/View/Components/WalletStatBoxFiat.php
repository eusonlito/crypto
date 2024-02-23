<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class WalletStatBoxFiat extends Component
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
     * @return bool
     */
    public function shouldRender(): bool
    {
        return boolval($this->list->isNotEmpty());
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.wallet.modules.stat-box-fiat', [
            'list' => $this->list,
        ]);
    }
}
