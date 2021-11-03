<?php declare(strict_types=1);

namespace App\Domains\Platform\Action;

use App\Domains\Shared\Action\ActionAbstract as ActionAbstractShared;
use App\Domains\Platform\Model\Platform as Model;

abstract class ActionAbstract extends ActionAbstractShared
{
    /**
     * @var ?\App\Domains\Platform\Model\Platform
     */
    protected ?Model $row;
}
