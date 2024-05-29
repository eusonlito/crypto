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
        $this->data['buy_stop_max_value'] = floatval($this->data['buy_stop_max_value']);
        $this->data['buy_stop_reference'] = floatval($this->data['buy_stop_reference']);
        $this->data['buy_stop_min_percent'] = abs(floatval($this->data['buy_stop_min_percent']));
        $this->data['buy_stop_max_percent'] = abs(floatval($this->data['buy_stop_max_percent']));

        if ($this->dataBuyStopIsEmpty()) {
            $this->dataBuyStopZero();

            return;
        }

        $this->data['buy_stop_min_exchange'] = $this->data['buy_stop_reference'] * (1 - ($this->data['buy_stop_min_percent'] / 100));
        $this->data['buy_stop_max_exchange'] = $this->data['buy_stop_min_exchange'] * (1 + ($this->data['buy_stop_max_percent'] / 100));

        $this->data['buy_stop_amount'] = $this->data['buy_stop_max_value'] / $this->data['buy_stop_max_exchange'];
        $this->data['buy_stop_min_value'] = $this->data['buy_stop_amount'] * $this->data['buy_stop_min_exchange'];

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
     * @return bool
     */
    protected function dataBuyStopIsEmpty(): bool
    {
        return empty($this->data['buy_stop_max_value'])
            || empty($this->data['buy_stop_reference'])
            || empty($this->data['buy_stop_min_percent'])
            || empty($this->data['buy_stop_max_percent']);
    }

    /**
     * @return void
     */
    protected function dataBuyStopZero(): void
    {
        $this->data['buy_stop'] = false;

        $this->data['buy_stop_amount'] = 0;
        $this->data['buy_stop_reference'] = 0;

        $this->data['buy_stop_max_percent'] = 0;
        $this->data['buy_stop_min_percent'] = 0;

        $this->data['buy_stop_max_exchange'] = 0;
        $this->data['buy_stop_min_exchange'] = 0;

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
        if ($this->data['buy_stop_min_exchange'] && empty($this->data['buy_stop_max_exchange'])) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-min-empty-max', [
                'max' => $this->data['buy_stop_max_exchange'],
                'min' => $this->data['buy_stop_min_exchange'],
            ]));
        }

        if (empty($this->data['buy_stop_min_exchange']) && $this->data['buy_stop_max_exchange']) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-max-empty-min', [
                'max' => $this->data['buy_stop_max_exchange'],
                'min' => $this->data['buy_stop_min_exchange'],
            ]));
        }

        if ($this->data['buy_stop_max_exchange'] < $this->data['buy_stop_min_exchange']) {
            throw new ValidatorException(__('wallet-update.error.buy-stop-max-less-min', [
                'max' => $this->data['buy_stop_max_exchange'],
                'min' => $this->data['buy_stop_min_exchange'],
            ]));
        }
    }
}
