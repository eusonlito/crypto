<?php declare(strict_types=1);

namespace App\Domains\Language\Action;

use App\Domains\Language\Model\Language as Model;
use App\Domains\Shared\Action\ActionAbstract as ActionAbstractService;

abstract class ActionAbstract extends ActionAbstractService
{
    /**
     * @var ?\App\Domains\Language\Model\Language
     */
    protected ?Model $row;
}
