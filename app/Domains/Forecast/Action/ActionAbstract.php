<?php declare(strict_types=1);

namespace App\Domains\Forecast\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Forecast\Model\Forecast as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Forecast\Model\Forecast
     */
    protected ?Model $row;
}
