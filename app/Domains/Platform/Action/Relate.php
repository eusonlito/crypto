<?php declare(strict_types=1);

namespace App\Domains\Platform\Action;

use App\Domains\Platform\Model\Platform as Model;
use App\Domains\Platform\Model\PlatformUser as PlatformUserModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\User\Model\User as UserModel;
use App\Exceptions\ValidatorException;
use App\Services\Platform\ApiFactoryAbstract;

class Relate extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\User\Model\User
     */
    protected UserModel $user;

    /**
     * @param \App\Domains\User\Model\User $user
     *
     * @return \App\Domains\Platform\Model\Platform
     */
    public function handle(UserModel $user): Model
    {
        $this->user = $user;

        $this->api();
        $this->check();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->row, $this->data);
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        if ($this->api->check() === false) {
            throw new ValidatorException(__('platform.error.credentials-invalid', ['name' => $this->row->name]));
        }
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        PlatformUserModel::updateOrCreate([
            'platform_id' => $this->row->id,
            'user_id' => $this->user->id,
        ], ['settings' => $this->data]);
    }
}
