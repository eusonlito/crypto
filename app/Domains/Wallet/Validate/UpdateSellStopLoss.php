<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class UpdateSellStopLoss extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sell_stoploss' => 'bail|boolean',
            'sell_stoploss_percent' => 'bail|numeric',
            'sell_stoploss_at' => 'boolean',
        ];
    }
}
