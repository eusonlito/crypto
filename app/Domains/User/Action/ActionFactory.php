<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authCredentials(): Model
    {
        return $this->actionHandle(AuthCredentials::class, $this->validate()->authCredentials());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authModel(): Model
    {
        return $this->actionHandle(AuthModel::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authTFA(): Model
    {
        return $this->actionHandle(AuthTFA::class, $this->validate()->authTFA());
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->actionHandle(Logout::class);
    }

    /**
     * @return bool
     */
    public function sessionTFA(): bool
    {
        return $this->actionHandle(SessionTFA::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function signup(): Model
    {
        return $this->actionHandle(Signup::class, $this->validate()->signup());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function updateBoolean(): Model
    {
        return $this->actionHandle(UpdateBoolean::class, $this->validate()->updateBoolean());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function updatePlatform(): Model
    {
        return $this->actionHandle(UpdatePlatform::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function update(): Model
    {
        return $this->actionHandle(Update::class, $this->validate()->update());
    }
}
