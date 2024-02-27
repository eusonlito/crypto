<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Ticker\Model\Ticker as TickerModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Index
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected Authenticatable $auth;

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
     * @return self
     */
    public static function new()
    {
        return new self(...func_get_args());
    }

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

        $this->wallets();
        $this->tickers();
        $this->exchanges();
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'orders' => $this->orders(),
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
            ->byProductIds($this->wallets->pluck('product_id')->merge($this->tickers->pluck('product_id')))
            ->chart($this->request->input('time'))
            ->toBase()
            ->get()
            ->groupBy('product_id');
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
    protected function orders(): Collection
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
    protected function walletsValues(): Collection
    {
        return WalletModel::query()->byUserId($this->auth->id)->get();
    }
}
