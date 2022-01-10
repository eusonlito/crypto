<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class UpdateBuyMarket extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'buy_market' => 'bail|boolean',
            'buy_market_amount' => 'bail|numeric',
            'buy_market_reference' => 'bail|numeric|required_with:buy_market_max_percent',
            'buy_market_percent' => 'bail|numeric',
            'buy_market_at' => 'boolean',
        ];
    }
}
