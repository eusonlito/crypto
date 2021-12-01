<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Ticker\Model\Ticker as TickerModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($this->hasWallets() === false) {
            return redirect()->route('dashboard.start');
        }

        $this->filters();

        $this->meta('title', __('dashboard-index.meta-title'));

        $wallets = $this->wallets();

        return $this->page('dashboard.index', [
            'filters' => $this->request->input(),
            'investment' => $this->auth->investment,
            'orders' => $this->orders(),
            'tickers' => $this->tickers(),
            'wallets' => $wallets,
            'walletsCrypto' => $wallets->where('crypto', true),
            'walletsFiat' => $wallets->where('crypto', false),
            'walletsValues' => $this->walletsValues(),
        ]);
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'time' => (int)$this->auth->preference('dashboard-time', $this->request->input('time'), 60),
            'references' => (bool)$this->auth->preference('dashboard-references', $this->request->input('references'), true),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function tickers(): Collection
    {
        return TickerModel::byUserId($this->auth->id)
            ->enabled()
            ->list()
            ->withExchangesChart($this->request->input('time'))
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function orders(): Collection
    {
        return OrderModel::byUserId($this->auth->id)
            ->whereFilled()
            ->list()
            ->limit(10)
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function wallets(): Collection
    {
        return WalletModel::byUserId($this->auth->id)
            ->enabled()
            ->whereVisible()
            ->list()
            ->withExchangesChart($this->request->input('time'))
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function walletsValues(): Collection
    {
        return WalletModel::byUserId($this->auth->id)->get();
    }
}
