<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Exchange\Model\Exchange as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Exchange\Model\Exchange
     */
    protected ?Model $row;
}
