<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Sync extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'platform_id' => 'bail|integer|nullable',
        ];
    }
}
