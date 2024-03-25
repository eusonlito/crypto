<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Wallet\Model\Wallet as Model;

class WalletChart extends Component
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @var bool
     */
    protected bool $references;

    /**
     * @var array
     */
    protected array $exchanges;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $orders;

    /**
     * @var string
     */
    protected string $dateFormat;

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param bool $references = true
     * @param ?\Illuminate\Support\Collection $orders = null
     *
     * @return self
     */
    public function __construct(
        Model $row,
        bool $references = true,
        ?Collection $orders = null
    ) {
        $this->row = $row;
        $this->references = $references;
        $this->orders = $orders ?: collect();

        $this->ordersFilter();
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
            'exchanges' => $this->exchanges(),
            'exchanges_count' => count($this->exchanges()),
            'dates' => $this->dates(),
            'orders' => $this->orders(),
        ];
    }

    /**
     * @return array
     */
    protected function exchanges(): array
    {
        if (isset($this->exchanges)) {
            return $this->exchanges;
        }

        $this->exchanges = array_merge(
            $this->row->exchanges->pluck('exchange', 'created_at')->all(),
            $this->orders->pluck('price', 'updated_at')->all(),
        );

        ksort($this->exchanges);

        return $this->exchanges;
    }

    /**
     * @return string
     */
    protected function dateFormat(): string
    {
        if (isset($this->dateFormat)) {
            return $this->dateFormat;
        }

        $first = array_key_first($this->exchanges());
        $day = date('Y-m-d H:i:s', strtotime('-1 day'));

        return $this->dateFormat = ($first < $day) ? 'd H:i' : 'H:i';
    }

    /**
     * @return array
     */
    protected function dates(): array
    {
        $format = $this->dateFormat();

        return array_map(
            fn ($value) => date($format, strtotime($value)),
            array_keys($this->exchanges())
        );
    }

    /**
     * @return void
     */
    protected function ordersFilter(): void
    {
        $first = $this->row->exchanges->first()?->created_at;

        $this->orders = $this->orders
            ->filter(fn ($value) => $value->updated_at >= $first);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function orders(): Collection
    {
        $format = $this->dateFormat();

        return $this->orders
            ->map(fn ($value) => [
                'index' => date($format, strtotime($value->updated_at)),
                'side' => $value->side,
                'type' => $value->type,
                'amount' => $value->amount,
                'exchange' => $value->price,
                'value' => $value->value,
            ])->values();
    }
}
