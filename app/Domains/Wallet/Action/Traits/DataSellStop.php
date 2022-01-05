<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

use App\Exceptions\ValidatorException;

trait DataSellStop
{
    /**
     * @return void
     */
    protected function dataSellStop(): void
    {
        $this->data['sell_stop_max_percent'] = abs((float)$this->data['sell_stop_max_percent']);
        $this->data['sell_stop_min_percent'] = abs((float)$this->data['sell_stop_min_percent']);

        if (($this->data['sell_stop_max_percent'] === 0.0) || ($this->data['sell_stop_min_percent'] === 0.0)) {
            $this->dataSellStopZero();
            return;
        }

        $this->data['sell_stop_amount'] = (float)$this->data['sell_stop_amount'];
        $this->data['sell_stop_exchange'] = (float)$this->data['sell_stop_exchange'];

        $this->data['sell_stop_max'] = $this->data['sell_stop_exchange'] * (1 + ($this->data['sell_stop_max_percent'] / 100));
        $this->data['sell_stop_min'] = $this->data['sell_stop_max'] * (1 - ($this->data['sell_stop_min_percent'] / 100));

        $this->data['sell_stop_max_value'] = $this->data['sell_stop_amount'] * $this->data['sell_stop_max'];
        $this->data['sell_stop_min_value'] = $this->data['sell_stop_amount'] * $this->data['sell_stop_min'];

        if ($this->data['sell_stop_max_at']) {
            $this->data['sell_stop_max_at'] = $this->row->sell_stop_max_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['sell_stop_max_at'] = null;
        }

        if ($this->data['sell_stop_min_at']) {
            $this->data['sell_stop_min_at'] = $this->row->sell_stop_min_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['sell_stop_min_at'] = null;
        }
    }

    /**
     * @return void
     */
    protected function dataSellStopZero(): void
    {
        $this->data['sell_stop'] = false;

        $this->data['sell_stop_amount'] = 0;
        $this->data['sell_stop_exchange'] = 0;

        $this->data['sell_stop_max_percent'] = 0;
        $this->data['sell_stop_min_percent'] = 0;

        $this->data['sell_stop_max'] = 0;
        $this->data['sell_stop_min'] = 0;

        $this->data['sell_stop_max_value'] = 0;
        $this->data['sell_stop_min_value'] = 0;

        $this->data['sell_stop_max_at'] = null;
        $this->data['sell_stop_min_at'] = null;
    }

    /**
     * @return void
     */
    protected function checkSellStop(): void
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
    }
}
