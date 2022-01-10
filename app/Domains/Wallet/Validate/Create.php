<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'address' => 'bail|required|string',
            'name' => 'bail|required|string',
            'order' => 'bail|integer',

            'amount' => 'bail|required|numeric',

            'buy_exchange' => 'bail|required|numeric',

            'sell_stop' => 'bail|boolean',
            'sell_stop_amount' => 'bail|numeric',
            'sell_stop_exchange' => 'bail|numeric|required_with:sell_stop_max_percent',
            'sell_stop_max' => 'bail|numeric',
            'sell_stop_max_percent' => 'bail|numeric',
            'sell_stop_min' => 'bail|numeric',
            'sell_stop_min_percent' => 'bail|numeric',
            'sell_stop_max_at' => 'boolean',
            'sell_stop_min_at' => 'boolean',

            'buy_stop' => 'bail|boolean',
            'buy_stop_amount' => 'bail|numeric',
            'buy_stop_exchange' => 'bail|numeric|required_with:buy_stop_max_percent',
            'buy_stop_max' => 'bail|numeric',
            'buy_stop_max_percent' => 'bail|numeric',
            'buy_stop_min' => 'bail|numeric',
            'buy_stop_min_percent' => 'bail|numeric',
            'buy_stop_max_at' => 'boolean',
            'buy_stop_min_at' => 'boolean',

            'buy_market' => 'bail|boolean',
            'buy_market_amount' => 'bail|numeric',
            'buy_market_reference' => 'bail|numeric|required_with:buy_market_percent',
            'buy_market_percent' => 'bail|numeric',
            'buy_market_exchange' => 'bail|numeric',
            'buy_market_value' => 'bail|numeric',
            'buy_market_at' => 'boolean',

            'sell_stoploss_exchange' => 'bail|numeric',
            'sell_stoploss_percent' => 'bail|numeric',
            'sell_stoploss' => 'bail|boolean',
            'sell_stoploss_at' => 'boolean',

            'visible' => 'bail|boolean',
            'enabled' => 'bail|boolean',

            'platform_id' => 'bail|required|integer|exists:platform,id',
            'product_id' => 'bail|required|integer',
        ];
    }
}
