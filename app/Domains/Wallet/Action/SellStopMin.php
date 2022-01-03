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
     * @var \stdClass
     */
    protected stdClass $previous;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->platform();
        $this->product();
        $this->logBefore();

        if ($this->executable() === false) {
            return tap($this->row, fn () => $this->logNotExecutable());
        }

        $this->previous();
        $this->start();
        $this->sync();
        $this->order();
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
        return (bool)$this->platform->userPivot
            && ($this->row->processing === false)
            && $this->row->enabled
            && $this->row->crypto
            && $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_min
            && $this->row->sell_stop_min_at
            && $this->row->sell_stop_min_executable
            && $this->row->sell_stop_max
            && $this->row->sell_stop_max_at;
    }

    /**
     * @return void
     */
    protected function previous(): void
    {
        $this->previous = (object)$this->row->toArray();
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
        $this->factory()->action()->updateSync();
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
        $this->updateExchange();
        $this->updateSellStop();
        $this->updateBuyStop();
        $this->updateProduct();
    }

    /**
     * @return void
     */
    protected function updateExchange(): void
    {
        if ($this->row->amount > $this->order->amount) {
            $this->row->amount -= $this->order->amount;
        }

        $this->row->buy_exchange = $this->order->price;
        $this->row->buy_value = $this->row->buy_exchange * $this->row->amount;
    }

    /**
     * @return void
     */
    protected function updateSellStop(): void
    {
        $this->row->sell_stop = false;
        $this->row->sell_stop_max_at = null;
        $this->row->sell_stop_min_at = null;
    }

    /**
     * @return void
     */
    protected function updateBuyStop(): void
    {
        if ($this->row->buy_stop_min_percent && $this->row->buy_stop_max_percent) {
            $this->row->buy_stop = true;
        }

        $this->row->buy_stop_min = $this->row->buy_exchange * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_value = $this->row->buy_stop_amount * $this->row->buy_stop_min;
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_max = $this->row->buy_stop_min * (1 + ($this->row->buy_stop_max_percent / 100));
        $this->row->buy_stop_max_value = $this->row->buy_stop_amount * $this->row->buy_stop_max;
        $this->row->buy_stop_max_at = null;
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
        $this->row->sell_stop_min_executable = false;
        $this->row->processing = false;
        $this->row->save();
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
        ActionLogger::set($status, 'sell-stop-min', $this->row, $data);
    }
}
