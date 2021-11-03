<?php declare(strict_types=1);

namespace App\Domains\Forecast\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class Selected extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'favorite' => 'bail|boolean',
        ];
    }
}
