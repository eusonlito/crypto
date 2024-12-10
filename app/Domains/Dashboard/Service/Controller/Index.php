<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Ticker\Model\Ticker as TickerModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Index extends ControllerAbstract
{
    /**
     * @var int
     */
    protected int $minutes;

    /**
     * @var string
     */
    protected string $date;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $tickers;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $orders;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $wallets;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges;

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     * @param \Illuminate\Http\Request $request
     *
     * @return self
     */
    public function __construct(Authenticatable $auth, Request $request)
    {
        $this->auth = $auth;
        $this->request = $request;

        $this->filters();
        $this->minutes();
        $this->date();
        $this->wallets();
        $this->tickers();
        $this->exchanges();
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'time' => intval($this->auth->preference('dashboard-time', $this->request->input('time'), 60)),
            'references' => boolval($this->auth->preference('dashboard-references', $this->request->input('references'), true)),
        ]);
    }

    /**
     * @return void
     */
    protected function minutes(): void
    {
        $this->minutes = max(0, min(intval($this->request->input('time')), 21600));
        $this->minutes = ($this->minutes === 1440) ? ($this->minutes - 1) : $this->minutes;
    }

    /**
     * @return void
     */
    protected function date(): void
    {
        $this->date = date('Y-m-d H:i:s', strtotime('-'.$this->minutes.' minutes'));
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'ordersFilled' => $this->ordersFilled(),
            'ordersOpen' => $this->ordersOpen(),
            'tickers' => $this->tickers,
            'wallets' => $this->wallets,
            'walletsCrypto' => $this->wallets->where('crypto', true),
            'walletsFiat' => $this->wallets->where('crypto', false),
            'walletsValues' => $this->walletsValues(),
        ];
    }

    /**
     * @return void
     */
    protected function wallets(): void
    {
        $this->wallets = WalletModel::query()
            ->byUserId($this->auth->id)
            ->enabled()
            ->whereVisible()
            ->withOrdersFilledAfterDate($this->date)
            ->list()
            ->get();
    }

    /**
     * @return void
     */
    protected function tickers(): void
    {
        $this->tickers = TickerModel::query()
            ->byUserId($this->auth->id)
            ->enabled()
            ->list()
            ->get();
    }

    /**
     * @return void
     */
    protected function exchanges(): void
    {
        $this->exchangesGet();
        $this->exchangesSet();
    }

    /**
     * @return void
     */
    protected function exchangesGet(): void
    {
        $this->exchanges = ExchangeModel::query()
            ->byProductIds($this->exchangesGetProductIds())
            ->chart($this->minutes)
            ->toBase()
            ->get()
            ->groupBy('product_id');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function exchangesGetProductIds(): Collection
    {
        return $this->wallets
            ->pluck('product_id')
            ->merge($this->tickers->pluck('product_id'));
    }

    /**
     * @return void
     */
    protected function exchangesSet(): void
    {
        foreach ($this->wallets as $each) {
            $each->setRelation('exchanges', $this->exchanges->get($each->product_id, collect()));
        }

        foreach ($this->tickers as $each) {
            $each->setRelation('exchanges', $this->exchanges->get($each->product_id, collect()));
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function ordersFilled(): Collection
    {
        return OrderModel::query()
            ->byUserId($this->auth->id)
            ->whereFilled()
            ->list()
            ->limit(10)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function ordersOpen(): Collection
    {
        return OrderModel::query()
            ->byUserId($this->auth->id)
            ->byStatus('new')
            ->byType('take_profit_limit')
            ->list()
            ->limit(10)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function walletsValues(): Collection
    {
        return WalletModel::query()->byUserId($this->auth->id)->get();
    }
}
