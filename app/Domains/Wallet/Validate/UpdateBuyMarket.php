<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class UpdateBuyMarket extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'bail|numeric|gt:0|required',
            'retry' => 'bail|integer|min:0',
        ];
    }
}
