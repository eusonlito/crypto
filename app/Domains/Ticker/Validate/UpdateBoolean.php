<?php declare(strict_types=1);

namespace App\Domains\Ticker\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class UpdateBoolean extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'column' => 'bail|required|string|in:enabled',
        ];
    }
}
