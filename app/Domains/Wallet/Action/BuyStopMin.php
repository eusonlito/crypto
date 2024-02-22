<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Throwable;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class BuyStopMin extends ActionAbstract
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
        $this->platform();
        $this->product();
        $this->logBefore();

        if ($this->executable() === false) {
            return tap($this->row, fn () => $this->logNotExecutable());
        }

        $this->start();
        $this->order();
        $this->update();
        $this->finish();
        $this->sync();
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
        return (bool)$this->platform->userPivot
            && ($this->row->processing === false)
            && $this->row->enabled
            && $this->row->crypto
            && $this->row->buy_stop
            && $this->row->buy_stop_amount
            && $this->row->buy_stop_min_exchange
            && $this->row->buy_stop_min_at
            && $this->row->buy_stop_min_executable
            && $this->row->buy_stop_max_exchange
            && ($this->row->buy_stop_max_at === null)
            && ($this->row->buy_stop_amount >= $this->product->quantity_min)
            && ($this->row->buy_stop_min_exchange >= $this->product->price_min);
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
        $this->order = $this->factory('Order')->action([
            'type' => 'stop_loss_limit',
            'side' => 'buy',
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
        $amount = $this->row->buy_stop_amount;
        $decimal = $this->product->quantity_decimal;

        $cash = $this->orderCreateSendAmountWalletQuote();

        if ($cash === null) {
            return helper()->roundFixed($amount, $decimal);
        }

        $value = $amount * $this->row->buy_stop_max_exchange;
        $max = $cash * 0.995;

        if ($value > $max) {
            $amount = $max * $amount / $value;
        }

        return helper()->roundFixed($amount, $decimal);
    }

    /**
     * @return ?float
     */
    protected function orderCreateSendAmountWalletQuote(): ?float
    {
        return Model::query()
            ->byProductCurrencyBaseIdAndCurrencyQuoteId($this->product->currency_quote_id, $this->product->currency_quote_id)
            ->byPlatformId($this->platform->id)
            ->value('current_value');
    }

    /**
     * @return float
     */
    protected function orderCreateSendPrice(): float
    {
        return $this->row->buy_stop_max_exchange;
    }

    /**
     * @return float
     */
    protected function orderCreateSendLimit(): float
    {
        $limit = $this->row->buy_stop_max_exchange;

        return $limit - ($limit * 0.0001);
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
        $this->row->buy_stop_min_executable = false;
        $this->row->processing = false;
        $this->row->save();
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
        ActionLogger::set($status, 'buy-stop-min', $this->row, $data + [
            'order' => $this->order,
        ]);
    }
}
