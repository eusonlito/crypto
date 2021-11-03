<?php declare(strict_types=1);

namespace App\Domains\Product\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;

class UpdateBoolean extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'column' => 'bail|required|string|in:tracking,enabled',
        ];
    }
}
