<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Ticker\Model\Ticker as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Ticker\Model\Ticker
     */
    protected ?Model $row;
}
