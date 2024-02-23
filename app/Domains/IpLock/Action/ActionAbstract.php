<?php declare(strict_types=1);

namespace App\Domains\IpLock\Action;

use App\Domains\IpLock\Model\IpLock as Model;
use App\Domains\Core\Action\ActionAbstract as ActionAbstractService;

abstract class ActionAbstract extends ActionAbstractService
{
    /**
     * @var ?\App\Domains\IpLock\Model\IpLock
     */
    protected ?Model $row;
}
