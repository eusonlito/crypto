<?php declare(strict_types=1);

namespace App\Domains\Order\Action\Traits;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

trait CreateUpdate
{
    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected WalletModel $wallet;

    /**
     * @return \App\Domains\Order\Model\Order
     */
    public function handle(): Model
    {
        $this->wallet();
        $this->product();
        $this->platform();

        $this->data();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function wallet(): void
    {
        $this->wallet = WalletModel::query()
            ->byUserId($this->auth->id)
            ->byId($this->data['wallet_id'])
            ->with(['product', 'platform'])
            ->firstOrFail();
    }

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = $this->wallet->product;
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->wallet->platform;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['code'] = '';
        $this->data['value'] = $this->data['amount'] * $this->data['price'];
        $this->data['type'] = strtolower($this->data['type']);
        $this->data['side'] = strtolower($this->data['side']);
        $this->data['status'] = 'filled';
        $this->data['filled'] = true;
        $this->data['custom'] = true;
    }
}
