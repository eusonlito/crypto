<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class SellStopTrailingCheck extends ActionAbstract
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
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?OrderModel $order = null;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        if ($this->row->processing_at) {
            return $this->row;
        }

        if (empty($this->row->platform->trailing_stop)) {
            return $this->row;
        }

        $this->start();

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->finish();
        }

        $this->order();

        if (empty($this->order)) {
            return $this->finish();
        }

        $this->update();
        $this->finish();

        $this->logSuccess();

        $this->buyStopTrailingCreate();

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
     * @return bool
     */
    protected function executable(): bool
    {
        if ($this->executableStatus()) {
            return true;
        }

        $this->logNotExecutable();

        return false;
    }

    /**
     * @return bool
     */
    protected function executableStatus(): bool
    {
        return (bool)$this->platform->userPivot
            && $this->row->enabled
            && $this->row->crypto
            && $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->order_sell_stop_id;
    }

    /**
     * @return void
     */
    protected function start(): void
    {
        $this->row->processing_at = date('Y-m-d H:i:s');
        $this->row->save();
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $this->orderSync();
        $this->orderLoad();
    }

    /**
     * @return void
     */
    protected function orderSync(): void
    {
        $this->factory('Order')->action()->syncByProducts(collect([$this->product]));
    }

    /**
     * @return void
     */
    protected function orderLoad(): void
    {
        $this->order = OrderModel::query()
            ->byId($this->row->order_sell_stop_id)
            ->whereFilled()
            ->first();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $this->updateSync();
        $this->updateRefresh();
        $this->updateBuy();
        $this->updateBuyStop();
        $this->updateSellStop();
        $this->updateSellStopLoss();
        $this->updateProduct();
    }

    /**
     * @return void
     */
    protected function updateSync(): void
    {
        $this->factory()->action()->updateSync();
    }

    /**
     * @return void
     */
    protected function updateRefresh(): void
    {
        $this->row = $this->row->fresh();
    }

    /**
     * @return void
     */
    protected function updateBuy(): void
    {
        $this->row->updateBuy($this->order->price);
    }

    /**
     * @return void
     */
    protected function updateBuyStop(): void
    {
        if ($this->row->buy_stop_min_percent) {
            $this->row->buy_stop_min_percent = max($this->row->buy_stop_min_percent - 0.5, 5);
            $this->row->buy_stop_max_value = $this->order->value;
        }

        $this->row->updateBuyStopEnable();
    }

    /**
     * @return void
     */
    protected function updateSellStop(): void
    {
        $this->row->updateSellStopDisable();
    }

    /**
     * @return void
     */
    protected function updateSellStopLoss(): void
    {
        $this->row->updateSellStopLossDisable();
    }

    /**
     * @return void
     */
    protected function updateProduct(): void
    {
        if (empty($this->product->tracking)) {
            return;
        }

        if (Model::query()->byProductId($this->product->id)->whereBuyOrSellPending()->count() > 1) {
            return;
        }

        $this->product->tracking = false;
        $this->product->save();
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    protected function finish(): Model
    {
        $this->row->processing_at = null;
        $this->row->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function buyStopTrailingCreate(): void
    {
        $this->factory()->action()->buyStopTrailingCreate();
    }

    /**
     * @return void
     */
    protected function logBefore(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logNotExecutable(): void
    {
        $this->log('error', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logSuccess(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);
    }

    /**
     * @param string $status
     * @param array $data = []
     *
     * @return void
     */
    protected function log(string $status, array $data = []): void
    {
        ActionLogger::set($status, 'sell-stop-trailing-check', $this->row, $data + [
            'order' => $this->order,
        ]);
    }
}
