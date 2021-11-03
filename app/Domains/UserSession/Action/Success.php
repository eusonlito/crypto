<?php declare(strict_types=1);

namespace App\Domains\UserSession\Action;

use App\Domains\User\Model\User as UserModel;
use App\Domains\UserSession\Model\UserSession as Model;

class Success extends ActionAbstract
{
    /**
     * @var \App\Domains\User\Model\User
     */
    protected UserModel $user;

    /**
     * @param \App\Domains\User\Model\User $user
     *
     * @return void
     */
    public function handle(UserModel $user): void
    {
        $this->user = $user;

        $this->store();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        Model::create([
            'auth' => $this->user->email,
            'ip' => $this->request->ip(),
            'success' => true,
            'user_id' => $this->user->id,
        ]);
    }
}
