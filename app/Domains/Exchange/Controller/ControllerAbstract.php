<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Exchange\Model\Exchange
     */
    protected ?Model $row;
}
