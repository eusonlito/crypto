<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\DataSellStop as DataSellStopTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class UpdateSellStop extends ActionAbstract
{
    use DataSellStopTrait;

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
        $this->dataSellStop();
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkSellStop();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->sell_stop = $this->data['sell_stop'];

        $this->row->sell_stop_amount = $this->data['sell_stop_amount'];
        $this->row->sell_stop_exchange = $this->data['sell_stop_exchange'];

        $this->row->sell_stop_max = $this->data['sell_stop_max'];
        $this->row->sell_stop_max_value = $this->data['sell_stop_max_value'];
        $this->row->sell_stop_max_percent = $this->data['sell_stop_max_percent'];
        $this->row->sell_stop_max_at = $this->data['sell_stop_max_at'];

        $this->row->sell_stop_min = $this->data['sell_stop_min'];
        $this->row->sell_stop_min_value = $this->data['sell_stop_min_value'];
        $this->row->sell_stop_min_percent = $this->data['sell_stop_min_percent'];
        $this->row->sell_stop_min_at = $this->data['sell_stop_min_at'];

        $this->row->save();
    }
}
