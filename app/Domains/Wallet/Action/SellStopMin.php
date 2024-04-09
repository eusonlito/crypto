<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use stdClass;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class SellStopMin extends ActionAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?OrderModel $order = null;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var ?\stdClass
     */
    protected ?stdClass $previous = null;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        if ($this->row->processing_at) {
            return $this->row;
        }

        if ($this->row->platform->trailing_stop) {
            return $this->row;
        }

        $this->start();

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->finish();
        }

        $this->previous();
        $this->sync();
        $this->refresh();
        $this->order();

        if (empty($this->order)) {
            return $this->finish();
        }

        $this->update();
        $this->finish();

        $this->logSuccess();
        $this->mail();

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
            && $this->row->sell_stop_min_exchange
            && $this->row->sell_stop_min_at
            && $this->row->sell_stop_min_executable
            && $this->row->sell_stop_max_at;
    }

    /**
     * @return void
     */
    protected function previous(): void
    {
        $this->previous = json_decode(json_encode($this->row->toArray()));
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
    protected function sync(): void
    {
        $this->factory()->action()->updateSync();
    }

    /**
     * @return void
     */
    protected function refresh(): void
    {
        $this->row = $this->row->fresh();
    }

    /**
     * @return void
     */
    protected function order(): void
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
        $this->updateBuy();
        $this->updateBuyStop();
        $this->updateSellStop();
        $this->updateSellStopLoss();
        $this->updateProduct();
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
    protected function mail(): void
    {
        $this->factory()->mail()->sellStopMin($this->row, $this->previous, $this->order);
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
        ActionLogger::set($status, 'sell-stop-min', $this->row, $data + [
            'order' => $this->order,
            'previous' => $this->previous,
        ]);
    }
}
