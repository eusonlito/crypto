<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Shared\Controller\ControllerWebAbstract;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;
}
