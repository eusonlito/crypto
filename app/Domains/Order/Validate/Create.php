<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['bail', 'required', 'in:LIMIT,MARKET,STOP_LOSS,STOP_LOSS_LIMIT,TAKE_PROFIT,TAKE_PROFIT_LIMIT,LIMIT_MAKER'],
            'side' => ['bail', 'required', 'in:buy,sell'],
            'amount' => ['bail', 'required', 'numeric'],
            'price' => ['bail', 'numeric'],
            'limit' => ['bail', 'numeric'],
        ];
    }
}
