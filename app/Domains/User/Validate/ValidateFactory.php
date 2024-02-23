<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

use App\Domains\Core\Validate\ValidateFactoryAbstract;

class ValidateFactory extends ValidateFactoryAbstract
{
    /**
     * @return array
     */
    public function authCredentials(): array
    {
        return $this->handle(AuthCredentials::class);
    }

    /**
     * @return array
     */
    public function authTFA(): array
    {
        return $this->handle(AuthTFA::class);
    }

    /**
     * @return array
     */
    public function signup(): array
    {
        return $this->handle(Signup::class);
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->handle(Update::class);
    }

    /**
     * @return array
     */
    public function updateBoolean(): array
    {
        return $this->handle(UpdateBoolean::class);
    }
}
