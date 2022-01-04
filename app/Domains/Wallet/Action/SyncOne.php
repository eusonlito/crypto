<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Model\WalletHistory as WalletHistoryModel;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\Wallet as WalletResource;

class SyncOne extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var ?\App\Services\Platform\Resource\Wallet
     */
    protected ?WalletResource $resource;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->platform();
        $this->product();
        $this->logBefore();

        if ($this->available() === false) {
            return tap($this->row, fn () => $this->logNotAvailable());
        }

        $this->api();
        $this->resource();

        if (empty($this->resource)) {
            return tap($this->row, fn () => $this->logResourceNotAvailable());
        }

        $this->save();
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
    protected function available(): bool
    {
        return (bool)$this->platform->userPivot;
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
    protected function resource(): void
    {
        $this->resource = $this->api->wallets()->firstWhere('address', $this->row->address);
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        if ($this->row->amount !== $this->resource->amount) {
            $this->storeUpdateHistory();
        }

        $this->storeDefaults();
    }

    /**
     * @return void
     */
    protected function storeUpdateHistory(): void
    {
        WalletHistoryModel::insert($this->row->withoutRelations()->replicate()->toArray() + ['wallet_id' => $this->row->id]);
    }

    /**
     * @return void
     */
    protected function storeDefaults(): void
    {
        $this->row->amount = $this->resource->amount;

        if (empty($this->row->buy_exchange) && $this->row->amount) {
            $this->storeDefaultBuyExchange();
        }

        if ($this->row->crypto) {
            $this->storeDefaultsCrypto();
        } else {
            $this->storeDefaultsFiat();
        }

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function storeDefaultBuyExchange(): void
    {
        $this->row->buy_exchange = OrderModel::byProductId($this->product->id)
            ->byUserId($this->auth->id)
            ->orderByLast()
            ->first()
            ->price ?? 0;
    }

    /**
     * @return void
     */
    protected function storeDefaultsCrypto(): void
    {
        $this->row->buy_value = $this->row->amount * $this->row->buy_exchange;

        if ($exchange = $this->storeDefaultsCryptoExchange()) {
            $this->row->current_exchange = $exchange;
            $this->row->current_value = $this->row->amount * $this->row->current_exchange;
        }
    }

    /**
     * @return void
     */
    protected function storeDefaultsFiat(): void
    {
        $this->row->buy_exchange = 1;
        $this->row->buy_value = $this->row->amount;

        $this->row->current_exchange = $this->row->buy_exchange;
        $this->row->current_value = $this->row->amount;

        $this->row->sell_stop = 0;

        $this->row->sell_stop_amount = $this->row->amount;

        $this->row->sell_stop_max_percent = 0;
        $this->row->sell_stop_max = $this->row->buy_exchange;
        $this->row->sell_stop_max_value = $this->row->buy_value;

        $this->row->sell_stop_min_percent = 0;
        $this->row->sell_stop_min = $this->row->buy_exchange;
        $this->row->sell_stop_min_value = $this->row->buy_value;

        $this->row->buy_stop = 0;

        $this->row->buy_stop_amount = $this->row->amount;

        $this->row->buy_stop_max_percent = 0;
        $this->row->buy_stop_max = $this->row->buy_exchange;
        $this->row->buy_stop_max_value = $this->row->buy_value;

        $this->row->buy_stop_min_percent = 0;
        $this->row->buy_stop_min = $this->row->buy_exchange;
        $this->row->buy_stop_min_value = $this->row->buy_value;

        $this->row->sell_stoploss = 0;
        $this->row->sell_stoploss_percent = 0;
        $this->row->sell_stoploss_exchange = 0;
        $this->row->sell_stoploss_value = 0;
    }

    /**
     * @return float
     */
    protected function storeDefaultsCryptoExchange(): float
    {
        if (empty($this->product->exchange)) {
            return 0;
        }

        return $this->product->exchange->exchange;
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
    protected function logNotAvailable(): void
    {
        $this->log('error', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logResourceNotAvailable(): void
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
        ActionLogger::set($status, 'sync-one', $this->row, $data);
    }
}
