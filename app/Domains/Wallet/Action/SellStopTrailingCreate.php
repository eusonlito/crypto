<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Throwable;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class SellStopTrailingCreate extends ActionAbstract
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
        if (empty($this->row->platform->trailing_stop)) {
            return $this->row;
        }

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->row;
        }

        $this->ai();
        $this->order();
        $this->update();
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
            && $this->row->sell_stop_min_exchange
            && $this->row->sell_stop_min_percent;
    }

    /**
     * @return void
     */
    protected function ai(): void
    {
        if ($this->row->sell_stop_ai) {
            $this->factory()->action()->sellStopTrailingAi();
        }
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        try {
            $this->orderCreate();
        } catch (Throwable $e) {
            $this->orderCreateError($e);
        }
    }

    /**
     * @return void
     */
    protected function orderCreate(): void
    {
        $this->order = $this->factory('Order')->action([
            'type' => 'take_profit_limit',
            'side' => 'sell',
            'amount' => $this->sellStopOrderCreateAmount(),
            'price' => $this->orderCreatePrice(),
            'limit' => $this->sellStopOrderCreateLimit(),
            'trailing' => $this->orderCreateTrailing(),
            'wallet_id' => $this->row->id,
        ])->create($this->product);
    }

    /**
     * @return float
     */
    protected function orderCreatePrice(): float
    {
        return $this->roundFixed($this->row->sell_stop_max_exchange, 'price');
    }

    /**
     * @return int
     */
    protected function orderCreateTrailing(): int
    {
        return intval($this->row->sell_stop_min_percent * 100);
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
    protected function update(): void
    {
        $this->row->order_sell_stop_id = $this->order->id;
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
        ActionLogger::set($status, 'sell-stop-trailing-create', $this->row, $data + [
            'order' => $this->order,
        ]);
    }
}
