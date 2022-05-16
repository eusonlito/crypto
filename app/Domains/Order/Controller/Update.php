<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Update extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id)
    {
        $this->row($id);

        if ($response = $this->actionPost('update')) {
            return $response;
        }

        $this->requestMergeWithRow();

        $this->meta('title', __('order-update.meta-title'));

        return $this->page('order.update', [
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
        return ['LIMIT', 'MARKET', 'STOP_LOSS', 'STOP_LOSS_LIMIT', 'TAKE_PROFIT', 'TAKE_PROFIT_LIMIT', 'LIMIT_MAKER'];
    }

    /**
     * @return array
     */
    protected function sides(): array
    {
        return ['buy', 'sell'];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(): RedirectResponse
    {
        service()->message()->success(__('order-update.success'));

        return redirect()->route('order.update', $this->action()->update()->id);
    }
}
