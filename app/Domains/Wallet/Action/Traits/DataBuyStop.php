<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

use App\Exceptions\ValidatorException;

trait DataBuyStop
{
    /**
     * @return void
     */
    protected function dataBuyStop(): void
    {
        $this->data['buy_stop_min_percent'] = abs((float)$this->data['buy_stop_min_percent']);
        $this->data['buy_stop_max_percent'] = abs((float)$this->data['buy_stop_max_percent']);

        if (($this->data['buy_stop_min_percent'] === 0.0) || ($this->data['buy_stop_max_percent'] === 0.0)) {
            $this->dataBuyStopZero();
            return;
        }

        $this->data['buy_stop_amount'] = (float)$this->data['buy_stop_amount'];
        $this->data['buy_stop_exchange'] = (float)$this->data['buy_stop_exchange'];

        $this->data['buy_stop_min'] = $this->data['buy_stop_exchange'] * (1 - ($this->data['buy_stop_min_percent'] / 100));
        $this->data['buy_stop_max'] = $this->data['buy_stop_min'] * (1 + ($this->data['buy_stop_max_percent'] / 100));

        $this->data['buy_stop_min_value'] = $this->data['buy_stop_amount'] * $this->data['buy_stop_min'];
        $this->data['buy_stop_max_value'] = $this->data['buy_stop_amount'] * $this->data['buy_stop_max'];

        if ($this->data['buy_stop_min_at']) {
            $this->data['buy_stop_min_at'] = $this->row->buy_stop_min_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['buy_stop_min_at'] = null;
        }

        if ($this->data['buy_stop_max_at']) {
            $this->data['buy_stop_max_at'] = $this->row->buy_stop_max_at ?? date('Y-m-d H:i:s');
        } else {
            $this->data['buy_stop_max_at'] = null;
        }
    }

    /**
     * @return void
     */
    protected function dataBuyStopZero(): void
    {
        $this->data['buy_stop'] = false;

        $this->data['buy_stop_amount'] = 0;
        $this->data['buy_stop_exchange'] = 0;

        $this->data['buy_stop_max_percent'] = 0;
        $this->data['buy_stop_min_percent'] = 0;

        $this->data['buy_stop_max'] = 0;
        $this->data['buy_stop_min'] = 0;

        $this->data['buy_stop_max_value'] = 0;
        $this->data['buy_stop_min_value'] = 0;

        $this->data['buy_stop_max_at'] = null;
        $this->data['buy_stop_min_at'] = null;
    }

    /**
     * @return void
     */
    protected function checkBuyStop(): void
    {
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
