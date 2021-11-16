<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Exceptions\ValidatorException;

trait CreateUpdate
{
    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = ProductModel::byId($this->data['product_id'])
            ->byPlatformId($this->row->platform_id ?? $this->data['platform_id'])
            ->firstOr(static function () {
                throw new ValidatorException(__('wallet.error.product-exists'));
            });
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['amount'] = (float)$this->data['amount'];

        $this->data['buy_exchange'] = (float)$this->data['buy_exchange'];
        $this->data['buy_value'] = $this->data['amount'] * $this->data['buy_exchange'];

        $this->data['current_exchange'] = $this->dataExchange();
        $this->data['current_value'] = $this->data['amount'] * $this->data['current_exchange'];

        $this->dataSellStop();
        $this->dataBuyStop();
        $this->dataStopLoss();
    }

    /**
     * @return void
     */
    protected function dataSellStop(): void
    {
        $this->data['sell_stop_amount'] = (float)$this->data['sell_stop_amount'];

        $this->data['sell_stop_max'] = (float)$this->data['sell_stop_max'];
        $this->data['sell_stop_max_value'] = $this->data['sell_stop_amount'] * $this->data['sell_stop_max'];
        $this->data['sell_stop_max_percent'] = abs((float)$this->data['sell_stop_max_percent']);

        if ($this->data['sell_stop_max_at']) {
            $this->data['sell_stop_max_at'] = $this->row->sell_stop_max_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['sell_stop_max_at'] = null;
        }

        $this->data['sell_stop_min'] = (float)$this->data['sell_stop_min'];
        $this->data['sell_stop_min_value'] = $this->data['sell_stop_amount'] * $this->data['sell_stop_min'];
        $this->data['sell_stop_min_percent'] = abs((float)$this->data['sell_stop_min_percent']);

        if ($this->data['sell_stop_min_at']) {
            $this->data['sell_stop_min_at'] = $this->row->sell_stop_min_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['sell_stop_min_at'] = null;
        }

        $this->data['sell_stop_percent'] = abs(helper()->percent($this->data['sell_stop_min'], $this->data['sell_stop_max']));
    }

    /**
     * @return void
     */
    protected function dataBuyStop(): void
    {
        $this->data['buy_stop_amount'] = (float)$this->data['buy_stop_amount'];

        $this->data['buy_stop_min'] = (float)$this->data['buy_stop_min'];
        $this->data['buy_stop_min_value'] = $this->data['buy_stop_amount'] * $this->data['buy_stop_min'];
        $this->data['buy_stop_min_percent'] = abs((float)$this->data['buy_stop_min_percent']);

        if ($this->data['buy_stop_min_at']) {
            $this->data['buy_stop_min_at'] = $this->row->buy_stop_min_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['buy_stop_min_at'] = null;
        }

        $this->data['buy_stop_max'] = (float)$this->data['buy_stop_max'];
        $this->data['buy_stop_max_value'] = $this->data['buy_stop_amount'] * $this->data['buy_stop_max'];
        $this->data['buy_stop_max_percent'] = abs((float)$this->data['buy_stop_max_percent']);

        if ($this->data['buy_stop_max_at']) {
            $this->data['buy_stop_max_at'] = $this->row->buy_stop_max_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['buy_stop_max_at'] = null;
        }

        $this->data['buy_stop_percent'] = abs(helper()->percent($this->data['buy_stop_min'], $this->data['buy_stop_max']));
    }

    /**
     * @return void
     */
    protected function dataStopLoss(): void
    {
        $this->data['sell_stoploss_exchange'] = (float)$this->data['sell_stoploss_exchange'];
        $this->data['sell_stoploss_value'] = $this->data['amount'] * $this->data['sell_stoploss_exchange'];
        $this->data['sell_stoploss_percent'] = abs((float)$this->data['sell_stoploss_percent']);
        $this->data['sell_stoploss_at'] = $this->data['sell_stoploss_at'] ? $this->row->sell_stoploss_at : null;
    }

    /**
     * @return float
     */
    protected function dataExchange(): float
    {
        return ExchangeModel::select('exchange')
            ->byProductId($this->product->id)
            ->orderBy('id', 'DESC')
            ->first()
            ->exchange ?? 0;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        if ($this->data['sell_stop_min'] && empty($this->data['sell_stop_max'])) {
            throw new ValidatorException(__('wallet-update.error.sell-stop-min-empty-max', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }

        if (empty($this->data['sell_stop_min']) && $this->data['sell_stop_max']) {
            throw new ValidatorException(__('wallet-update.error.sell-stop-max-empty-min', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }

        if ($this->data['sell_stop_max'] < $this->data['sell_stop_min']) {
            throw new ValidatorException(__('wallet-update.error.sell-stop-max-less-min', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }

        if ($this->data['buy_stop_min'] && empty($this->data['buy_stop_max'])) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-min-empty-max', [
                'max' => $this->data['buy_stop_max'],
                'min' => $this->data['buy_stop_min'],
            ]));
        }

        if (empty($this->data['buy_stop_min']) && $this->data['buy_stop_max']) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-max-empty-min', [
                'max' => $this->data['buy_stop_max'],
                'min' => $this->data['buy_stop_min'],
            ]));
        }

        if ($this->data['buy_stop_max'] < $this->data['buy_stop_min']) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-max-less-min', [
                'max' => $this->data['buy_stop_max'],
                'min' => $this->data['buy_stop_min'],
            ]));
        }
    }
}
