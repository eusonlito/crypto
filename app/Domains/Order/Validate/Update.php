<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Update extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'created_at' => ['bail', 'required', 'date_format:Y-m-d H:i:s'],
            'amount' => ['bail', 'required', 'numeric'],
            'price' => ['bail', 'required', 'numeric'],
            'fee' => ['bail', 'numeric'],
            'type' => ['bail', 'required', 'in:limit,market,stop_loss,stop_loss_limit,take_profit,take_profit_limit,limit_maker'],
            'side' => ['bail', 'required', 'in:buy,sell'],
            'wallet_id' => ['bail', 'required', 'integer'],
        ];
    }
}
