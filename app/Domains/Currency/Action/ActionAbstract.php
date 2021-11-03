<?php declare(strict_types=1);

namespace App\Domains\Currency\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Currency\Model\Currency as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Currency\Model\Currency
     */
    protected ?Model $row;
}
