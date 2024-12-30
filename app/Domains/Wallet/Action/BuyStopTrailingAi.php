<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Action\Traits\DataBuyStop as DataBuyStopTrait;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Trader\Simple\Buy as TraderBuy;

class BuyStopTrailingAi extends ActionAbstract
{
    use DataBuyStopTrait;

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
     * @var array
     */
    protected array $values;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        if ($this->available() === false) {
            return $this->row;
        }

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->row;
        }

        $this->api();
        $this->calculate();
        $this->update();
        $this->logSuccess();

        return $this->row;
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return $this->row->enabled
            && $this->row->crypto
            && $this->row->buy_stop
            && $this->row->buy_stop_ai;
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
    protected function calculate(): void
    {
        $this->values = TraderBuy::new($this->product, $this->api)->calculate();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $this->updateData();
        $this->updateRow();
    }

    /**
     * @return void
     */
    protected function updateData(): void
    {
        $this->data = [
            'buy_stop_max_value' => $this->row->buy_stop_max_value,
            'buy_stop_reference' => $this->row->buy_stop_reference,
            'buy_stop_min_percent' => $this->updateDataBuyStopMinPercent(),
            'buy_stop_max_percent' => $this->values['max_percent'],
            'buy_stop_max_at' => $this->row->buy_stop_max_at,
            'buy_stop_min_at' => $this->row->buy_stop_min_at,
        ];

        $this->dataBuyStop();
    }

    /**
     * @return float
     */
    protected function updateDataBuyStopMinPercent(): float
    {
        return $this->values['min_percent']
            + $this->updateDataBuyStopMinPercentStopLossCount();
    }

    /**
     * @return int
     */
    protected function updateDataBuyStopMinPercentStopLossCount(): int
    {
        return OrderModel::query()
            ->byProductId($this->product->id)
            ->byCreatedAtAfter(date('Y-m-d H:i:s', strtotime('-5 days')))
            ->whereFilled()
            ->whereStopLoss()
            ->count();
    }

    /**
     * @return void
     */
    protected function updateRow(): void
    {
        $this->row->buy_stop_amount = $this->data['buy_stop_amount'];

        $this->row->buy_stop_min_exchange = $this->data['buy_stop_min_exchange'];
        $this->row->buy_stop_min_value = $this->data['buy_stop_min_value'];
        $this->row->buy_stop_min_percent = $this->data['buy_stop_min_percent'];

        $this->row->buy_stop_max_exchange = $this->data['buy_stop_max_exchange'];
        $this->row->buy_stop_max_value = $this->data['buy_stop_max_value'];
        $this->row->buy_stop_max_percent = $this->data['buy_stop_max_percent'];

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
        ActionLogger::set($status, 'buy-stop-trailing-ai', $this->row, $data);
    }
}
