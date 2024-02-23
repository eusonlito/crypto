<?php declare(strict_types=1);

namespace App\Domains\Forecast\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Forecast\Model\Forecast as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Forecast\Model\Forecast
     */
    protected ?Model $row;
}
