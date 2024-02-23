<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Update extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email:filter'],
            'password' => ['bail', 'min:8'],
            'investment' => ['bail', 'numeric'],
            'tfa_enabled' => ['bail', 'boolean'],
            'password_current' => ['bail', 'required', 'current_password'],
        ];
    }
}
