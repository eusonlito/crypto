<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Shared\Validate\ValidateAbstract;
use App\Domains\Shared\Validate\Rule\CSRF;

class Signup extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email:filter'],
            'password' => ['bail', 'required', 'min:8', 'confirmed'],
            'code' => ['bail', 'required'],
            '_token' => ['bail', 'required', new CSRF()],
        ];
    }
}
