<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class UpdateSellMarket extends ActionAbstract
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
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->start();

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->finish();
        }

        $this->order();
        $this->sync();
        $this->refresh();
        $this->update();
        $this->finish();

        $this->buyStopTrailingCreate();

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
            && $this->row->crypto;
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
        $this->order = $this->factory('Order')->action([
            'type' => 'market',
            'side' => 'sell',
            'amount' => $this->data['amount'],
            'wallet_id' => $this->row->id,
        ])->create($this->product);
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
        $this->factory('Order')->action()->syncByProducts(collect([$this->product]));
    }

    /**
     * @return void
     */
    protected function syncWallet(): void
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
    protected function update(): void
    {
        $this->updateOrder();
        $this->updateBuy();
        $this->updateBuyStop();
        $this->updateSellStop();
        $this->updateSellStopLoss();
    }

    /**
     * @return void
     */
    protected function updateOrder(): void
    {
        $this->order = OrderModel::query()->findOrFail($this->order->id);
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
    protected function mail(): void
    {
        $this->factory()->mail()->sellMarket($this->row, $this->order);
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
        ActionLogger::set($status, 'sell-market', $this->row, $data + [
            'order' => $this->order,
        ]);
    }
}
