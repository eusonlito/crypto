<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action;

use App\Domains\Ticker\Action\Traits\CreateUpdate as CreateUpdateTrait;

class Update extends ActionAbstract
{
    use CreateUpdateTrait;

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->amount = $this->data['amount'];

        $this->row->exchange_reference = $this->data['exchange_reference'];
        $this->row->exchange_current = $this->data['exchange_current'];
        $this->row->exchange_min = $this->data['exchange_min'];
        $this->row->exchange_max = $this->data['exchange_max'];

        $this->row->value_reference = $this->data['value_reference'];
        $this->row->value_current = $this->data['value_current'];
        $this->row->value_min = $this->data['value_min'];
        $this->row->value_max = $this->data['value_max'];

        $this->row->date_at = $this->data['date_at'];

        $this->row->enabled = $this->data['enabled'];

        $this->row->product_id = $this->data['product_id'];

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function message(): void
    {
        service()->message()->success(__('ticker-update.success'));
    }
}
