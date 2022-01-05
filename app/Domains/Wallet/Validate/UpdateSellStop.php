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
            'sell_stop_exchange' => 'bail|numeric|required_with:sell_stop_max_percent',
            'sell_stop_max' => 'bail|numeric',
            'sell_stop_max_percent' => 'bail|numeric',
            'sell_stop_min' => 'bail|numeric',
            'sell_stop_min_percent' => 'bail|numeric',
            'sell_stop' => 'bail|boolean',
            'sell_stop_max_at' => 'boolean',
            'sell_stop_min_at' => 'boolean',
        ];
    }
}
