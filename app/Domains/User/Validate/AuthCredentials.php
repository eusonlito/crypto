<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;
use App\Domains\Shared\Validate\Rule\CSRF;

class AuthCredentials extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email:filter'],
            'password' => ['bail', 'required', 'string'],
            '_token' => ['bail', 'required', new CSRF()],
        ];
    }
}
