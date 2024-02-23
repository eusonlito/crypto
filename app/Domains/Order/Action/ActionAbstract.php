<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Order\Model\Order as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;
}
