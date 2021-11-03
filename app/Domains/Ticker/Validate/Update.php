<?php declare(strict_types=1);

namespace App\Domains\Ticker\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Update extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount' => 'bail|required|numeric',
            'exchange_reference' => 'bail|required|numeric',

            'date_at' => 'bail|date_format:Y-m-d H:i:s',

            'enabled' => 'bail|boolean',

            'product_id' => 'bail|required|integer',
        ];
    }
}
