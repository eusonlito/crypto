<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Ticker\Model\Ticker as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Ticker\Model\Ticker
     */
    protected ?Model $row;
}
