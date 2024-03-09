<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'reference' => ['bail'],
            'type' => ['bail', 'required', 'in:limit,market,stop_loss,stop_loss_limit,take_profit,take_profit_limit,limit_maker'],
            'side' => ['bail', 'required', 'in:buy,sell'],
            'amount' => ['bail', 'required', 'numeric'],
            'price' => ['bail', 'numeric'],
            'limit' => ['bail', 'numeric'],
        ];
    }
}
