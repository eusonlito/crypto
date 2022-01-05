<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class UpdateBuyStop extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'buy_stop_amount' => 'bail|numeric',
            'buy_stop_exchange' => 'bail|numeric|required_with:buy_stop_max_percent',
            'buy_stop_max' => 'bail|numeric',
            'buy_stop_max_percent' => 'bail|numeric',
            'buy_stop_min' => 'bail|numeric',
            'buy_stop_min_percent' => 'bail|numeric',
            'buy_stop' => 'bail|boolean',
            'buy_stop_max_at' => 'boolean',
            'buy_stop_min_at' => 'boolean',
        ];
    }
}
