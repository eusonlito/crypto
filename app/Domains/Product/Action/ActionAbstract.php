<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Product\Model\Product as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Product\Model\Product
     */
    protected ?Model $row;
}
