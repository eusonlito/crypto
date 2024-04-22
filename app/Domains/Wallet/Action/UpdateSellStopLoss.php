<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\DataSellStopLoss as DataSellStopLossTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class UpdateSellStopLoss extends ActionAbstract
{
    use DataSellStopLossTrait;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataDefault();
        $this->dataSellStopLoss();
    }

    /**
     * @return void
     */
    protected function dataDefault(): void
    {
        $this->data += $this->row->toArray();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->processing_at = null;

        $this->row->sell_stoploss = $this->data['sell_stoploss'];

        $this->row->sell_stoploss_exchange = $this->data['sell_stoploss_exchange'];
        $this->row->sell_stoploss_value = $this->data['sell_stoploss_value'];
        $this->row->sell_stoploss_percent = $this->data['sell_stoploss_percent'];
        $this->row->sell_stoploss_at = $this->data['sell_stoploss_at'];

        $this->row->save();
    }
}
