<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class CreateUpdateFromResources extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'platform_id' => ['bail', 'required', 'integer'],
        ];
    }
}
