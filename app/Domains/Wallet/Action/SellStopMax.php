<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Throwable;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

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
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?OrderModel $order = null;

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

        $this->order();
        $this->update();
        $this->sync();
        $this->refresh();
        $this->finish();

        $this->logSuccess();

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
            && $this->row->amount
            && $this->row->sell_stop
            && $this->row->sell_stop_amount
            && $this->row->sell_stop_max_exchange
            && $this->row->sell_stop_max_at
            && $this->row->sell_stop_max_executable
            && $this->row->sell_stop_min_exchange
            && ($this->row->sell_stop_min_at === null)
            && ($this->row->sell_stop_amount >= $this->product->quantity_min)
            && ($this->row->sell_stop_min_exchange >= $this->product->price_min);
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
    }

    /**
     * @return void
     */
    protected function orderCreate(): void
    {
        try {
            $this->orderCreateSend();
        } catch (Throwable $e) {
            $this->orderCreateError($e);
        }
    }

    /**
     * @return void
     */
    protected function orderCreateSend(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);

        $this->order = $this->factory('Order')->action([
            'type' => 'stop_loss_limit',
            'side' => 'sell',
            'amount' => $this->orderCreateSendAmount(),
            'price' => $this->orderCreateSendPrice(),
            'limit' => $this->orderCreateSendLimit(),
        ])->create($this->product);
    }

    /**
     * @return float
     */
    protected function orderCreateSendAmount(): float
    {
        return min($this->row->amount, $this->row->sell_stop_amount);
    }

    /**
     * @return float
     */
    protected function orderCreateSendPrice(): float
    {
        return $this->row->sell_stop_min_exchange;
    }

    /**
     * @return float
     */
    protected function orderCreateSendLimit(): float
    {
        $limit = $this->row->sell_stop_min_exchange;

        return $limit + ($limit * 0.0001);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function orderCreateError(Throwable $e): void
    {
        $this->orderCreateErrorReport($e);
        $this->orderCreateErrorLog($e);
        $this->sync();

        throw $e;
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function orderCreateErrorReport(Throwable $e): void
    {
        report($e);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function orderCreateErrorLog(Throwable $e): void
    {
        $this->log('error', [
            'detail' => __FUNCTION__,
            'exception' => $e->getMessage(),
        ]);
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
    protected function sync(): void
    {
        $this->syncUpdate();
        $this->syncOrder();
    }

    /**
     * @return void
     */
    protected function syncUpdate(): void
    {
        $this->factory()->action()->updateSync();
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
    protected function refresh(): void
    {
        $this->row = $this->row->fresh();
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
        ActionLogger::set($status, 'sell-stop-max', $this->row, $data + [
            'order' => $this->order,
        ]);
    }
}
