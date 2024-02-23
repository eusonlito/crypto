<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Domains\User\Model\User as Model;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return void
     */
    protected function rowAuth(): void
    {
        $this->row = $this->auth;
    }
}
