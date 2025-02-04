<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Throwable;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;
use App\Exceptions\UnexpectedValueException;
use App\Services\Platform\ApiFactoryAbstract;

class UpdateBuyMarket extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

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

        $this->api();
        $this->order();
        $this->sync();
        $this->refresh();
        $this->update();
        $this->finish();

        $this->sellStopTrailingCreate();

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
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $e = null;
        $retry = time() + intval($this->data['retry']);

        do {
            try {
                $this->orderSend();

                if ($this->order) {
                    return;
                }

                sleep(1);
            } catch (Throwable $e) {
                report($e);
            }
        } while (time() <= $retry);

        if ($e) {
            throw $e;
        }

        throw new UnexpectedValueException(__('wallet-update-buy-market.error.timeout'));
    }

    /**
     * @return void
     */
    protected function orderSend(): void
    {
        $exchange = $this->orderSendExchange();

        if (empty($exchange)) {
            return;
        }

        $this->order = $this->factory('Order')->action([
            'type' => 'market',
            'side' => 'buy',
            'amount' => $this->orderSendAmount($exchange),
            'wallet_id' => $this->row->id,
        ])->create($this->product);
    }

    /**
     * @return ?float
     */
    protected function orderSendExchange(): ?float
    {
        return $this->api->exchange($this->product->code)?->price;
    }

    /**
     * @param float $exchange
     *
     * @return float
     */
    protected function orderSendAmount(float $exchange): float
    {
        return $this->roundFixed($this->data['value'] / $exchange, 'quantity');
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
        $this->row->updateBuyStopDisable();
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
    protected function sellStopTrailingCreate(): void
    {
        $this->factory()->action()->sellStopTrailingCreate();
    }

    /**
     * @return void
     */
    protected function mail(): void
    {
        $this->factory()->mail()->buyMarket($this->row, $this->order);
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
        ]);
    }
}
