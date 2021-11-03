<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action\Traits;

use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Ticker\Model\Ticker as Model;
use App\Exceptions\ValidatorException;

trait CreateUpdate
{
    /**
     * @var \App\Domains\Product\Model\Product $product
     */
    protected ProductModel $product;

    /**
     * @return \App\Domains\Ticker\Model\Ticker
     */
    public function handle(): Model
    {
        $this->product();
        $this->data();
        $this->store();
        $this->message();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = ProductModel::byId($this->data['product_id'])
            ->byPlatformId($this->row->platform_id ?? $this->data['platform_id'])
            ->firstOr(static function () {
                throw new ValidatorException(__('ticker.error.product-exists'));
            });
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['amount'] = (float)$this->data['amount'];

        $this->data['exchange_reference'] = (float)$this->data['exchange_reference'];
        $this->data['exchange_current'] = $this->dataExchangeCurrent();
        $this->data['exchange_min'] = $this->dataExchangeMin();
        $this->data['exchange_max'] = $this->dataExchangeMax();

        $this->data['value_reference'] = $this->data['amount'] * $this->data['exchange_reference'];
        $this->data['value_current'] = $this->data['amount'] * $this->data['exchange_current'];
        $this->data['value_min'] = $this->data['amount'] * $this->data['exchange_min'];
        $this->data['value_max'] = $this->data['amount'] * $this->data['exchange_max'];
    }

    /**
     * @return float
     */
    protected function dataExchangeCurrent(): float
    {
        return ExchangeModel::byProductId($this->product->id)->orderBy('id', 'DESC')->first()->exchange;
    }

    /**
     * @return float
     */
    protected function dataExchangeMin(): float
    {
        return (float)ExchangeModel::byProductId($this->product->id)->afterDate($this->data['date_at'])->min('exchange');
    }

    /**
     * @return float
     */
    protected function dataExchangeMax(): float
    {
        return (float)ExchangeModel::byProductId($this->product->id)->afterDate($this->data['date_at'])->max('exchange');
    }
}
