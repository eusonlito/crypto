<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\BuySellStop as BuySellStopLogger;

class BuyStopMax extends ActionAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?OrderModel $order;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var bool
     */
    protected bool $executable;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->platform();
        $this->product();
        $this->executable();
        $this->log();

        if ($this->executable === false) {
            return $this->row;
        }

        $this->start();
        $this->sync();
        $this->order();
        $this->update();
        $this->finish();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->row->platform;
        $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = $this->row->product;
        $this->product->setRelation('platform', $this->platform);
    }

    /**
     * @return void
     */
    protected function executable(): void
    {
        $this->executable = (bool)$this->platform->userPivot
            && ($this->row->processing === false)
            && $this->row->enabled
            && $this->row->crypto
            && $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_max
            && $this->row->buy_stop_max_at
            && $this->row->buy_stop_max_executable
            && $this->row->buy_stop_min
            && $this->row->buy_stop_min_at;
    }

    /**
     * @return void
     */
    protected function log(): void
    {
        BuySellStopLogger::set('wallet-buy-stop-max', $this->row, $this->executable);
    }

    /**
     * @return void
     */
    protected function start(): void
    {
        $this->row->processing = true;
        $this->row->save();
    }

    /**
     * @return void
     */
    protected function sync(): void
    {
        $this->syncOrder();
        $this->syncWallet();
    }

    /**
     * @return void
     */
    protected function syncOrder(): void
    {
        $this->factory('Order')->action()->syncByProduct($this->product);
    }

    /**
     * @return void
     */
    protected function syncWallet(): void
    {
        $this->factory()->action()->syncOne();
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $this->order = OrderModel::byProductId($this->product->id)
            ->byWalletId($this->row->id)
            ->orderByLast()
            ->first();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        if (empty($this->order->filled)) {
            return;
        }

        $this->updateExchange();
        $this->updateBuyStop();
        $this->updateSellStop();
        $this->updateProduct();
    }

    /**
     * @return void
     */
    protected function updateExchange(): void
    {
        $this->row->buy_exchange = $this->row->buy_stop_max;
        $this->row->buy_value = $this->row->buy_exchange * $this->row->amount;
    }

    /**
     * @return void
     */
    protected function updateBuyStop(): void
    {
        $this->row->buy_stop = false;
        $this->row->buy_stop_min_at = null;
        $this->row->buy_stop_max_at = null;
    }

    /**
     * @return void
     */
    protected function updateSellStop(): void
    {
        if ($this->row->sell_stop_amount && $this->row->sell_stop_max_percent && $this->row->sell_stop_percent) {
            $this->row->sell_stop_amount = $this->row->amount;
            $this->row->sell_stop = true;
        }

        $this->row->sell_stop_max = $this->row->buy_exchange * (1 + ($this->row->sell_stop_max_percent / 100));
        $this->row->sell_stop_max_value = $this->row->sell_stop_amount * $this->row->sell_stop_max;
        $this->row->sell_stop_max_at = null;

        $this->row->sell_stop_min = $this->row->sell_stop_max * (1 - ($this->row->sell_stop_percent / 100));
        $this->row->sell_stop_min_value = $this->row->sell_stop_amount * $this->row->sell_stop_min;
        $this->row->sell_stop_min_at = null;
    }

    /**
     * @return void
     */
    protected function updateProduct(): void
    {
        if (empty($this->product->tracking)) {
            return;
        }

        if (Model::byProductId($this->product->id)->whereBuyOrSellPending()->count() > 1) {
            return;
        }

        $this->product->tracking = false;
        $this->product->save();
    }

    /**
     * @return void
     */
    protected function finish(): void
    {
        $this->row->buy_stop_max_executable = false;
        $this->row->processing = false;
        $this->row->save();
    }
}
