<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Action\Traits\CreateUpdate as CreateUpdateTrait;

class Update extends ActionAbstract
{
    use CreateUpdateTrait;

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->amount = $this->data['amount'];
        $this->row->price = $this->data['price'];
        $this->row->value = $this->data['value'];
        $this->row->fee = $this->data['fee'];

        $this->row->type = $this->data['type'];
        $this->row->status = $this->data['status'];
        $this->row->side = $this->data['side'];

        $this->row->created_at = $this->data['created_at'];
        $this->row->updated_at = $this->data['created_at'];

        $this->row->platform_id = $this->platform->id;
        $this->row->product_id = $this->product->id;
        $this->row->user_id = $this->auth->id;
        $this->row->wallet_id = $this->wallet->id;

        $this->row->save();
    }
}
