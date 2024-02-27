<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Illuminate\Support\Collection;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Model\WalletHistory as WalletHistoryModel;
use App\Exceptions\UnexpectedValueException;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\Wallet as WalletResource;

class Sync extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $currencies;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $orders;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        if ($this->available() === false) {
            return;
        }

        $this->current();
        $this->products();
        $this->currencies();
        $this->api();
        $this->orders();
        $this->iterate();
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function current(): void
    {
        $this->current = Model::query()
            ->byUserId($this->auth->id)
            ->byPlatformId($this->platform->id)
            ->get()
            ->keyBy('address');
    }

    /**
     * @return void
     */
    protected function currencies(): void
    {
        $this->currencies = CurrencyModel::query()
            ->byPlatformId($this->platform->id)
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProductModel::query()
            ->byPlatformId($this->platform->id)
            ->withCurrencies()
            ->withExchange()
            ->get();
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
    protected function orders(): void
    {
        $this->orders = OrderModel::query()
            ->byUserId($this->auth->id)
            ->byPlatformId($this->platform->id)
            ->withProduct()
            ->bySide('buy')
            ->whereFilled()
            ->list()
            ->get();
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->api->wallets() as $each) {
            $this->store($each);
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return void
     */
    protected function store(WalletResource $resource): void
    {
        if ($resource->trading === false) {
            return;
        }

        if ($row = $this->storeRow($resource)) {
            $this->storeUpdate($row, $resource, $row->product ? $this->orderByProduct($row->product) : null);
        } elseif ($resource->amount) {
            $this->storeCreate($resource, $this->orderByWallet($resource));
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return ?\App\Domains\Wallet\Model\Wallet
     */
    protected function storeRow(WalletResource $resource): ?Model
    {
        return $this->current->get($resource->address);
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \App\Services\Platform\Resource\Wallet $resource
     * @param ?\App\Domains\Order\Model\Order $order
     *
     * @return void
     */
    protected function storeUpdate(Model $row, WalletResource $resource, ?OrderModel $order): void
    {
        if ($row->amount !== $resource->amount) {
            $this->storeUpdateHistory($row);
        }

        $this->storeDefaults($row, $resource, $order);
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return void
     */
    protected function storeUpdateHistory(Model $row): void
    {
        WalletHistoryModel::query()->create([
            'address' => $row->address,
            'name' => $row->name,

            'payload' => $row->withoutRelations()->toArray(),

            'currency_id' => $row->currency_id,
            'platform_id' => $row->platform_id,
            'product_id' => $row->product_id,
            'user_id' => $row->user_id,
            'wallet_id' => $row->id,
        ]);
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     * @param ?\App\Domains\Order\Model\Order $order
     *
     * @return void
     */
    protected function storeCreate(WalletResource $resource, ?OrderModel $order): void
    {
        $row = $this->storeCreateNew($resource);

        if ($order) {
            $this->storeCreateProductOrder($row, $order);
        } else {
            $this->storeCreateProductResource($row, $resource);
        }

        $this->storeDefaults($row, $resource, $order);
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \App\Domains\Order\Model\Order $order
     *
     * @return void
     */
    protected function storeCreateProductOrder(Model $row, OrderModel $order): void
    {
        $row->product_id = $order->product_id;
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return void
     */
    protected function storeCreateProductResource(Model $row, WalletResource $resource): void
    {
        $product = $this->productByCode($resource->symbol) ?: $this->productRelated($resource);

        if (empty($product)) {
            throw new UnexpectedValueException(__('wallet-sync.product-not-found', [
                'symbol' => $resource->symbol,
                'platform' => $this->platform->name,
            ]));
        }

        $row->crypto = $product->crypto;
        $row->product_id = $product->id;
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return \App\Domains\Wallet\Model\Wallet
     */
    protected function storeCreateNew(WalletResource $resource): Model
    {
        $currency = $this->currencyByCode($resource->symbol);

        if (empty($currency)) {
            throw new UnexpectedValueException(__('wallet-sync.currency-not-found', [
                'symbol' => $resource->symbol,
                'platform' => $this->platform->name,
            ]));
        }

        return new Model([
            'address' => $resource->address,
            'name' => $resource->symbol,
            'amount' => $resource->amount,
            'crypto' => $resource->crypto,
            'trade' => $resource->trading,
            'custom' => false,
            'visible' => true,
            'enabled' => true,
            'currency_id' => $currency->id,
            'platform_id' => $this->platform->id,
            'user_id' => $this->auth->id,
        ]);
    }

    /**
     * @param string $code
     *
     * @return ?\App\Domains\Currency\Model\Currency
     */
    protected function currencyByCode(string $code): ?CurrencyModel
    {
        return $this->currencies->get($code);
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return ?\App\Domains\Order\Model\Order
     */
    protected function orderByWallet(WalletResource $resource): ?OrderModel
    {
        return $this->orders->whereIn('product_id', $this->productsByWallet($resource)->pluck('id'))->first();
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return ?\App\Domains\Order\Model\Order
     */
    protected function orderByProduct(ProductModel $product): ?OrderModel
    {
        return $this->orders->firstWhere('product_id', $product->id);
    }

    /**
     * @param string $code
     *
     * @return ?\App\Domains\Product\Model\Product
     */
    protected function productByCode(string $code): ?ProductModel
    {
        return $this->products->firstWhere('code', $code);
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return \Illuminate\Support\Collection
     */
    protected function productsByWallet(WalletResource $resource): Collection
    {
        return $this->products->where('currencyBase.code', $resource->symbol);
    }

    /**
     * @param \App\Services\Platform\Resource\Wallet $resource
     *
     * @return ?\App\Domains\Product\Model\Product
     */
    protected function productRelated(WalletResource $resource): ?ProductModel
    {
        $products = $this->productsByWallet($resource);

        return $products->firstWhere('currencyQuote.code', 'EUR')
            ?: $products->firstWhere('currencyQuote.code', 'USD')
            ?: $products->firstWhere('currencyQuote.code', 'BTC')
            ?: $products->firstWhere('currencyQuote.code', 'BNB')
            ?: $products->firstWhere('currencyQuote.code', 'USDT')
            ?: $products->firstWhere('currencyQuote.code', 'USDC')
            ?: $products->firstWhere('currencyQuote.code', 'BUSD');
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \App\Services\Platform\Resource\Wallet $resource
     * @param ?\App\Domains\Order\Model\Order $order
     *
     * @return void
     */
    protected function storeDefaults(Model $row, WalletResource $resource, ?OrderModel $order): void
    {
        $row->amount = $resource->amount;

        if ($row->crypto) {
            $this->storeDefaultsCrypto($row, $order);
        } else {
            $this->storeDefaultsFiat($row);
        }

        $row->save();
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param ?\App\Domains\Order\Model\Order $order
     *
     * @return void
     */
    protected function storeDefaultsCrypto(Model $row, ?OrderModel $order): void
    {
        if (empty($row->buy_exchange)) {
            $row->buy_exchange = floatval($order->price ?? $row->buy_exchange);
        }

        $row->buy_value = $row->amount * $row->buy_exchange;

        if ($exchange = $this->storeDefaultsCryptoExchange($row)) {
            $row->current_exchange = $exchange;
            $row->current_value = $row->amount * $row->current_exchange;
        }
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return void
     */
    protected function storeDefaultsFiat(Model $row): void
    {
        $row->buy_exchange = 1;
        $row->buy_value = $row->amount;

        $row->current_exchange = $row->buy_exchange;
        $row->current_value = $row->amount;

        $row->sell_stop = false;

        $row->sell_stop_amount = 0;
        $row->sell_stop_reference = 0;

        $row->sell_stop_max_percent = 0;
        $row->sell_stop_max_exchange = 0;
        $row->sell_stop_max_value = 0;

        $row->sell_stop_min_percent = 0;
        $row->sell_stop_min_exchange = 0;
        $row->sell_stop_min_value = 0;

        $row->buy_stop = false;

        $row->buy_stop_amount = 0;
        $row->buy_stop_reference = 0;

        $row->buy_stop_max_percent = 0;
        $row->buy_stop_max_exchange = 0;
        $row->buy_stop_max_value = 0;

        $row->buy_stop_min_percent = 0;
        $row->buy_stop_min_exchange = 0;
        $row->buy_stop_min_value = 0;

        $row->buy_market = false;

        $row->buy_market_amount = 0;
        $row->buy_market_reference = 0;
        $row->buy_market_percent = 0;
        $row->buy_market_exchange = 0;
        $row->buy_market_value = 0;

        $row->sell_stoploss = false;
        $row->sell_stoploss_percent = 0;
        $row->sell_stoploss_exchange = 0;
        $row->sell_stoploss_value = 0;
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return float
     */
    protected function storeDefaultsCryptoExchange(Model $row): float
    {
        $product = $this->products->firstWhere('id', $row->product_id);

        if (empty($product->exchange)) {
            return 0;
        }

        return $product->exchange->exchange;
    }
}
