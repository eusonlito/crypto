<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Exchange\Model\Exchange as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Exchange\Model\Exchange
     */
    protected ?Model $row;
}
