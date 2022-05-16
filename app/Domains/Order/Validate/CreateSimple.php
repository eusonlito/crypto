<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class CreateSimple extends ValidateAbstract
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
            'type' => ['bail', 'required', 'in:LIMIT,MARKET,STOP_LOSS,STOP_LOSS_LIMIT,TAKE_PROFIT,TAKE_PROFIT_LIMIT,LIMIT_MAKER'],
            'side' => ['bail', 'required', 'in:buy,sell'],
            'wallet_id' => ['bail', 'required', 'integer'],
        ];
    }
}
