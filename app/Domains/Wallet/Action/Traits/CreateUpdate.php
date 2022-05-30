<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Exceptions\ValidatorException;

trait CreateUpdate
{
    use DataBuyMarket, DataBuyStop, DataSellStop, DataSellStopLoss;

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

        $this->dataBuyStop();
        $this->dataBuyMarket();

        $this->dataSellStop();
        $this->dataSellStopLoss();
    }

    /**
     * @return float
     */
    protected function dataExchange(): float
    {
        return $this->dataExchangeLast()
            ?: $this->dataExchangeCurrent()
            ?: 0;
    }

    /**
     * @return float
     */
    protected function dataExchangeLast(): float
    {
        return ExchangeModel::select('exchange')
            ->byProductId($this->product->id)
            ->orderBy('id', 'DESC')
            ->first()
            ->exchange ?? 0;
    }

    /**
     * @return float
     */
    protected function dataExchangeCurrent(): float
    {
        return $this->row->current_exchange ?? 0;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkSellStop();
        $this->checkBuyStop();
    }
}
