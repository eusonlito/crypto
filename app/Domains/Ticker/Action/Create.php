<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action;

use App\Domains\Ticker\Action\Traits\CreateUpdate as CreateUpdateTrait;
use App\Domains\Ticker\Model\Ticker as Model;

class Create extends ActionAbstract
{
    use CreateUpdateTrait;

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row = Model::create([
            'amount' => $this->data['amount'],

            'exchange_reference' => $this->data['exchange_reference'],
            'exchange_current' => $this->data['exchange_current'],
            'exchange_min' => $this->data['exchange_min'],
            'exchange_max' => $this->data['exchange_max'],

            'value_reference' => $this->data['value_reference'],
            'value_current' => $this->data['value_current'],
            'value_min' => $this->data['value_min'],
            'value_max' => $this->data['value_max'],

            'date_at' => $this->data['date_at'],

            'enabled' => $this->data['enabled'],

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

            'platform_id' => $this->data['platform_id'],
            'currency_id' => $this->product->currency_base_id,
            'product_id' => $this->product->id,
            'user_id' => $this->auth->id,
        ]);
    }

    /**
     * @return void
     */
    protected function message(): void
    {
        service()->message()->success(__('ticker-create.success'));
    }
}
