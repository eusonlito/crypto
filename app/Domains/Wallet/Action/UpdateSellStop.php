<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Exceptions\ValidatorException;

class UpdateSellStop extends ActionAbstract
{
    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
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
    protected function check(): void
    {
        if ($this->data['sell_stop_min'] && empty($this->data['sell_stop_max'])) {
            throw new ValidatorException(__('wallet-update-sell-stop.error.sell-stop-min-empty-max', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }

        if (empty($this->data['sell_stop_min']) && $this->data['sell_stop_max']) {
            throw new ValidatorException(__('wallet-update-sell-stop.error.sell-stop-max-empty-min', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }

        if ($this->data['sell_stop_max'] < $this->data['sell_stop_min']) {
            throw new ValidatorException(__('wallet-update-sell-stop.error.sell-stop-max-less-min', [
                'max' => $this->data['sell_stop_max'],
                'min' => $this->data['sell_stop_min'],
            ]));
        }
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->sell_stop_amount = $this->data['sell_stop_amount'];

        $this->row->sell_stop_max = $this->data['sell_stop_max'];
        $this->row->sell_stop_max_value = $this->data['sell_stop_max_value'];
        $this->row->sell_stop_max_percent = $this->data['sell_stop_max_percent'];
        $this->row->sell_stop_max_at = $this->data['sell_stop_max_at'];

        $this->row->sell_stop_min = $this->data['sell_stop_min'];
        $this->row->sell_stop_min_value = $this->data['sell_stop_min_value'];
        $this->row->sell_stop_min_percent = $this->data['sell_stop_min_percent'];
        $this->row->sell_stop_min_at = $this->data['sell_stop_min_at'];

        $this->row->sell_stop_percent = $this->data['sell_stop_percent'];

        $this->row->sell_stop = $this->data['sell_stop'];

        $this->row->save();
    }
}
