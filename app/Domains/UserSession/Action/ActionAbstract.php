<?php declare(strict_types=1);

namespace App\Domains\UserSession\Action;

use App\Domains\UserSession\Model\UserSession as Model;
use App\Domains\Shared\Action\ActionAbstract as ActionAbstractService;

abstract class ActionAbstract extends ActionAbstractService
{
    /**
     * @var ?\App\Domains\UserSession\Model\UserSession
     */
    protected ?Model $row;
}
