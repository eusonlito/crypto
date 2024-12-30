<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Action\Traits\DataSellStop as DataSellStopTrait;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Trader\Simple\Sell as TraderSell;

class SellStopTrailingAi extends ActionAbstract
{
    use DataSellStopTrait;

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
            && $this->row->sell_stop
            && $this->row->sell_stop_ai;
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
        $this->values = TraderSell::new($this->product, $this->api)->calculate();
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
            'sell_stop_percent' => $this->row->sell_stop_percent,
            'sell_stop_reference' => $this->values['reference'],
            'sell_stop_max_percent' => $this->values['max_percent'],
            'sell_stop_min_percent' => $this->values['min_percent'],
            'sell_stop_max_at' => $this->row->sell_stop_max_at,
            'sell_stop_min_at' => $this->row->sell_stop_min_at,
        ];

        $this->dataSellStop();
    }

    /**
     * @return void
     */
    protected function updateRow(): void
    {
        $this->row->sell_stop_max_exchange = $this->data['sell_stop_max_exchange'];
        $this->row->sell_stop_max_value = $this->data['sell_stop_max_value'];
        $this->row->sell_stop_max_percent = $this->data['sell_stop_max_percent'];

        $this->row->sell_stop_min_exchange = $this->data['sell_stop_min_exchange'];
        $this->row->sell_stop_min_value = $this->data['sell_stop_min_value'];
        $this->row->sell_stop_min_percent = $this->data['sell_stop_min_percent'];

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
        ActionLogger::set($status, 'sell-stop-trailing-ai', $this->row, $data);
    }
}
