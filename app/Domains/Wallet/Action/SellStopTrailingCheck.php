<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Throwable;
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
        if ($this->row->processing) {
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
            return $this->row;
        }

        $this->sync();
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
            && $this->row->order_sell_stop_id;
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
        $this->syncUpdate();
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
