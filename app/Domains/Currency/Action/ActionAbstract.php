<?php declare(strict_types=1);

namespace App\Domains\Currency\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Currency\Model\Currency as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Currency\Model\Currency
     */
    protected ?Model $row;
}
