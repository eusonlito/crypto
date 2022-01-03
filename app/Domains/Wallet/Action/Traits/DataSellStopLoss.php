<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Exceptions\ValidatorException;

trait DataSellStopLoss
{
    /**
     * @return void
     */
    protected function dataStopLoss(): void
    {
        $this->data['sell_stoploss_percent'] = abs((float)$this->data['sell_stoploss_percent']);

        if ($this->data['sell_stoploss_percent'] === 0.0) {
            $this->dataStopLossZero();
            return;
        }

        $this->data['sell_stoploss_exchange'] = $this->data['buy_exchange'] * (1 - ($this->data['sell_stoploss_percent'] / 100));
        $this->data['sell_stoploss_value'] = $this->data['amount'] * $this->data['sell_stoploss_exchange'];
        $this->data['sell_stoploss_at'] = $this->data['sell_stoploss_at'] ? $this->row->sell_stoploss_at : null;
    }

    /**
     * @return void
     */
    protected function dataStopLossZero(): void
    {
        $this->data['sell_stoploss_percent'] = 0;
        $this->data['sell_stoploss_exchange'] = 0;
        $this->data['sell_stoploss_value'] = 0;
        $this->data['sell_stoploss_at'] = null;
    }
}