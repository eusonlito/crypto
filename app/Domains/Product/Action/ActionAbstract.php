<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Product\Model\Product as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Product\Model\Product
     */
    protected ?Model $row;
}
