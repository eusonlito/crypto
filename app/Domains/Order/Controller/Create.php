<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Create extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($response = $this->actionPost('create')) {
            return $response;
        }

        $this->meta('title', __('order-create.meta-title'));

        return $this->page('order.create', [
            'wallets' => $this->wallets(),
            'wallet' => $this->wallet(),
            'types' => $this->types(),
            'sides' => $this->sides(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function wallets(): Collection
    {
        return WalletModel::byUserId($this->auth->id)
            ->with(['platform', 'product'])
            ->orderBy('name', 'ASC')
            ->get()
            ->each(static fn ($value) => $value->title = $value->title());
    }

    /**
     * @return ?\App\Domains\Wallet\Model\Wallet
     */
    protected function wallet(): ?WalletModel
    {
        return WalletModel::byUserId($this->auth->id)
            ->byId((int)$this->request->input('wallet_id'))
            ->with(['platform', 'product'])
            ->first();
    }

    /**
     * @return array
     */
    protected function types(): array
    {
        return [
            'limit' => 'LIMIT',
            'market' => 'MARKET',
            'stop_loss' => 'STOP_LOSS',
            'stop_loss_limit' => 'STOP_LOSS_LIMIT',
            'take_profit' => 'TAKE_PROFIT',
            'take_profit_limit' => 'TAKE_PROFIT_LIMIT',
            'limit_maker' => 'LIMIT_MAKER',
        ];
    }

    /**
     * @return array
     */
    protected function sides(): array
    {
        return [
            'buy' => __('order-create.side-buy'),
            'sell' => __('order-create.side-sell'),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function create(): RedirectResponse
    {
        $row = $this->action()->createSimple();

        service()->message()->success(__('order-create.success'));

        return redirect()->route('order.update', $row->id);
    }
}
