<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use stdClass;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class BuyStopMax extends ActionAbstract
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
        if ($this->row->processing) {
            return $this->row;
        }

        $this->start();

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->row;
        }

        $this->previous();
        $this->sync();
        $this->refresh();
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
        if ($this->executableStatus()) {
            return true;
        }

        $this->logNotExecutable();
        $this->finish();

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
            && $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_max_exchange
            && $this->row->buy_stop_max_at
            && $this->row->buy_stop_max_executable
            && $this->row->buy_stop_min_exchange
            && $this->row->buy_stop_min_at;
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
            ->byWalletId($this->row->id)
            ->bySide('buy')
            ->whereFilled()
            ->orderByLast()
            ->first();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $this->updateBuy();
        $this->updateBuyStop();
        $this->updateBuyMarket();
        $this->updateSellStop();
        $this->updateSellStopLoss();
        $this->updateProduct();
    }

    /**
     * @return void
     */
    protected function updateBuy(): void
    {
        $this->row->updateBuy($this->updateBuyValue());
    }

    /**
     * @return float
     */
    protected function updateBuyValue(): float
    {
        return OrderModel::query()
            ->lastSame($this->order)
            ->rawValue('SUM(`price` * `amount`) / SUM(`amount`)')
            ?: $this->order->price;
    }

    /**
     * @return void
     */
    protected function updateBuyStop(): void
    {
        $this->row->updateBuyStopDisable();
    }

    /**
     * @return void
     */
    protected function updateBuyMarket(): void
    {
        $this->row->updateBuyMarketDisable();
    }

    /**
     * @return void
     */
    protected function updateSellStop(): void
    {
        $this->row->updateSellStopEnable();
    }

    /**
     * @return void
     */
    protected function updateSellStopLoss(): void
    {
        $this->row->updateSellStopLossEnable();
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
     * @return void
     */
    protected function finish(): void
    {
        $this->row->processing = false;
        $this->row->save();
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->buyStopMax($this->row, $this->previous, $this->order);
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
        ActionLogger::set($status, 'buy-stop-max', $this->row, $data + [
            'order' => $this->order,
            'previous' => $this->previous,
        ]);
    }
}
