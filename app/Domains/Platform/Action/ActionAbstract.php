<?php declare(strict_types=1);

namespace App\Domains\Platform\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Platform\Model\Platform as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Platform\Model\Platform
     */
    protected ?Model $row;
}
