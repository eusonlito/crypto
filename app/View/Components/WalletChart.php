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

        $this->dateFormat();
        $this->index();
        $this->filter();
        $this->exchanges();
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
            'references' => $this->references,
            'exchanges' => $this->exchanges,
            'exchanges_count' => count($this->exchanges),
            'dates' => $this->dates(),
            'orders' => $this->orders(),
        ];
    }

    /**
     * @return void
     */
    protected function dateFormat(): void
    {
        $first = $this->row->exchanges->first()?->created_at;
        $day = date('Y-m-d H:i:s', strtotime('-1 day'));

        $this->dateFormat = ($first < $day) ? 'd H:i' : 'H:i';
    }

    /**
     * @return array
     */
    protected function dates(): array
    {
        return array_keys($this->exchanges);
    }

    /**
     * @return void
     */
    protected function index(): void
    {
        $this->indexOrders();
    }

    /**
     * @return void
     */
    protected function indexOrders(): void
    {
        $this->orders->each(
            fn ($order) => $order->index = date($this->dateFormat, strtotime($order->updated_at))
        );
    }

    /**
     * @return void
     */
    protected function filter(): void
    {
        $this->filterOrders();
    }

    /**
     * @return void
     */
    protected function filterOrders(): void
    {
        $first = $this->row->exchanges->first()?->created_at;

        $this->orders = $this->orders
            ->filter(fn ($value) => $value->updated_at >= $first);
    }

    /**
     * @return void
     */
    protected function exchanges(): void
    {
        $order_index = $this->orders->pluck('price', 'index')->all();

        $exchanges = $this->orders->pluck('price', 'updated_at')->all()
            + $this->row->exchanges->pluck('exchange', 'created_at')->all();

        ksort($exchanges);

        $this->exchanges = [];

        foreach ($exchanges as $index => $value) {
            $index = date($this->dateFormat, strtotime($index));
            $this->exchanges[$index] = $order_index[$index] ?? $value;
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function orders(): Collection
    {
        return $this->orders
            ->map(fn ($value) => [
                'index' => $value->index,
                'side' => $value->side,
                'type' => $value->type,
                'amount' => $value->amount,
                'exchange' => $value->price,
                'value' => $value->value,
            ])->values();
    }
}
