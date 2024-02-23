<?php declare(strict_types=1);

namespace App\Domains\UserSession\Action;

use App\Domains\UserSession\Model\UserSession as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\UserSession\Model\UserSession
     */
    protected ?Model $row;

    /**
     * @return void
     */
    public function fail(): void
    {
        $this->actionHandle(Fail::class);
    }

    /**
     * @return void
     */
    public function success(): void
    {
        $this->actionHandle(Success::class, [], ...func_get_args());
    }
}
