<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use stdClass;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class BuyMarket extends ActionAbstract
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
            return $this->finish();
        }

        $this->previous();
        $this->order();
        $this->sync();
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
            && $this->row->buy_market
            && $this->row->buy_market_amount
            && $this->row->buy_market_exchange
            && $this->row->buy_market_at
            && $this->row->buy_market_executable
            && empty($this->row->buy_stop_min_at)
            && empty($this->row->sell_stop_max_at)
            && ($this->row->buy_market_amount >= $this->product->quantity_min)
            && ($this->row->buy_market_exchange >= $this->product->price_min);
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
            'type' => 'market',
            'side' => 'buy',
            'amount' => $this->row->buy_market_amount,
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
        $this->factory('Order')->action()->syncByProducts([$this->product]);
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
        $this->row->updateBuy($this->order->price);
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
     * @return \App\Domains\Wallet\Model\Wallet
     */
    protected function finish(): Model
    {
        $this->row->processing = false;
        $this->row->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->buyMarket($this->row, $this->previous, $this->order);
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
        ActionLogger::set($status, 'buy-market', $this->row, $data + [
            'order' => $this->order,
            'previous' => $this->previous,
        ]);
    }
}
