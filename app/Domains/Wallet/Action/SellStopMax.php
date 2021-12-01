<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\BuySellStop as BuySellStopLogger;

class SellStopMax extends ActionAbstract
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
     * @var \App\Domains\Order\Model\Order
     */
    protected OrderModel $order;

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
            && $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_max
            && $this->row->sell_stop_max_at
            && $this->row->sell_stop_max_executable
            && $this->row->sell_stop_min
            && ($this->row->sell_stop_min_at === null)
            && ($this->row->sell_stop_amount <= $this->row->amount)
            && ($this->row->sell_stop_amount >= $this->product->quantity_min)
            && ($this->row->sell_stop_min >= $this->product->price_min);
    }

    /**
     * @return void
     */
    protected function log(): void
    {
        BuySellStopLogger::set('wallet-sell-stop-max', $this->row, $this->executable);
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
    protected function order(): void
    {
        $this->orderCreate();
        $this->orderUpdate();
        $this->orderSync();
    }

    /**
     * @return void
     */
    protected function orderCreate(): void
    {
        $this->order = $this->factory('Order')->action([
            'type' => 'STOP_LOSS_LIMIT',
            'side' => 'sell',
            'amount' => $this->row->sell_stop_amount,
            'price' => $this->row->sell_stop_min,
            'limit' => $this->row->sell_stop_min,
        ])->create($this->product);
    }

    /**
     * @return void
     */
    protected function orderUpdate(): void
    {
        $this->order->wallet_id = $this->row->id;
        $this->order->save();
    }

    /**
     * @return void
     */
    protected function orderSync(): void
    {
        $this->factory('Order')->action()->syncByProduct($this->product);
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        if ($this->product->tracking) {
            return;
        }

        $this->product->tracking = true;
        $this->product->save();
    }

    /**
     * @return void
     */
    protected function finish(): void
    {
        $this->row->sell_stop_max_executable = false;
        $this->row->processing = false;
        $this->row->save();
    }
}
