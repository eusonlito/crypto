<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\DataBuyStop as DataBuyStopTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class UpdateBuyStop extends ActionAbstract
{
    use DataBuyStopTrait;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->store();
        $this->orderCanceOpen();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataBuyStop();
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkBuyStop();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->processing = false;

        $this->row->buy_stop = $this->data['buy_stop'];

        $this->row->buy_stop_amount = $this->data['buy_stop_amount'];
        $this->row->buy_stop_reference = $this->data['buy_stop_reference'];

        $this->row->buy_stop_max_exchange = $this->data['buy_stop_max_exchange'];
        $this->row->buy_stop_max_value = $this->data['buy_stop_max_value'];
        $this->row->buy_stop_max_percent = $this->data['buy_stop_max_percent'];
        $this->row->buy_stop_max_follow = $this->data['buy_stop_max_follow'];
        $this->row->buy_stop_max_at = $this->data['buy_stop_max_at'];

        $this->row->buy_stop_min_exchange = $this->data['buy_stop_min_exchange'];
        $this->row->buy_stop_min_value = $this->data['buy_stop_min_value'];
        $this->row->buy_stop_min_percent = $this->data['buy_stop_min_percent'];
        $this->row->buy_stop_min_at = $this->data['buy_stop_min_at'];

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function orderCanceOpen(): void
    {
        if (empty($this->row->buy_stop)) {
            $this->factory('Order')->action()->cancelOpen($this->row->product);
        }
    }
}
