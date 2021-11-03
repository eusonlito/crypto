<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Order\Model\Order as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;
}
