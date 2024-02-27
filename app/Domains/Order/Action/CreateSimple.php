<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Order\Action\Traits\CreateUpdate as CreateUpdateTrait;

class CreateSimple extends ActionAbstract
{
    use CreateUpdateTrait;

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row = Model::query()->create([
            'code' => $this->data['code'],

            'amount' => $this->data['amount'],
            'price' => $this->data['price'],
            'value' => $this->data['value'],
            'fee' => $this->data['fee'],

            'type' => $this->data['type'],
            'status' => $this->data['status'],
            'side' => $this->data['side'],

            'filled' => $this->data['filled'],
            'custom' => $this->data['custom'],

            'created_at' => $this->data['created_at'],
            'updated_at' => $this->data['created_at'],

            'platform_id' => $this->platform->id,
            'product_id' => $this->product->id,
            'user_id' => $this->auth->id,
            'wallet_id' => $this->wallet->id,
        ]);
    }
}
