<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class UpdateSellStop extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sell_stop_amount' => 'bail|numeric',
            'sell_stop_reference' => 'bail|numeric',
            'sell_stop_max_exchange' => 'bail|numeric',
            'sell_stop_max_percent' => 'bail|numeric',
            'sell_stop_min_exchange' => 'bail|numeric',
            'sell_stop_min_percent' => 'bail|numeric',
            'sell_stop' => 'bail|boolean',
            'sell_stop_max_at' => 'boolean',
            'sell_stop_min_at' => 'boolean',
        ];
    }
}
