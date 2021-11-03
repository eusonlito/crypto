<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\BuySellStop as BuySellStopLogger;
use App\Services\Platform\ApiFactoryAbstract;

class SellStopLoss extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\Order\Model\Order
     */
    protected OrderModel $order;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var bool
     */
    protected bool $executable;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->platform();
        $this->product();
        $this->executable();
        $this->log();

        if ($this->executable === false) {
            return $this->row;
        }

        $this->start();
        $this->api();
        $this->order();
        $this->sync();
        $this->buyStop();
        $this->finish();

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
     * @return void
     */
    protected function executable(): void
    {
        $this->executable = (bool)$this->platform->userPivot
            && ($this->row->processing === false)
            && $this->row->enabled
            && $this->row->crypto
            && $this->row->amount
            && $this->row->sell_stoploss
            && $this->row->sell_stoploss_exchange
            && $this->row->sell_stoploss_at
            && ($this->row->amount > $this->product->quantity_min);
    }

    /**
     * @return void
     */
    protected function log(): void
    {
        BuySellStopLogger::set('wallet-sell-stop-loss', $this->row, $this->executable);
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
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $this->api->orderCreateMarket(
            $this->product->code,
            'sell',
            helper()->roundFixed($this->row->amount, $this->product->quantity_decimal)
        );
    }

    /**
     * @return void
     */
    protected function sync(): void
    {
        $this->syncOrder();
        $this->syncWallet();
        $this->syncRefresh();
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
    protected function syncWallet(): void
    {
        $this->factory()->action()->sync($this->platform);
    }

    /**
     * @return void
     */
    protected function syncRefresh(): void
    {
        $this->row->refresh();
    }

    /**
     * @return void
     */
    protected function buyStop(): void
    {
        $this->row->buy_stop_min_percent = $this->row->buy_stop_min_percent ?: 10;
        $this->row->buy_stop_min = $this->row->buy_exchange * (1 - ($this->row->buy_stop_min_percent / 100));
        $this->row->buy_stop_min_value = $this->row->buy_stop_amount * $this->row->buy_stop_min;
        $this->row->buy_stop_min_at = null;

        $this->row->buy_stop_min_percent = $this->row->buy_stop_max_percent ?: 5;
        $this->row->buy_stop_max = $this->row->buy_stop_min * (1 + ($this->row->buy_stop_max_percent / 100));
        $this->row->buy_stop_max_value = $this->row->buy_stop_amount * $this->row->buy_stop_max;
        $this->row->buy_stop_max_at = null;

        $this->row->buy_stop_percent = helper()->percent($this->row->buy_stop_min, $this->row->buy_stop_max);
    }

    /**
     * @return void
     */
    protected function finish(): void
    {
        $this->row->processing = false;
        $this->row->save();
    }
}
