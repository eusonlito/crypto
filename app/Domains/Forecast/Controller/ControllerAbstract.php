<?php declare(strict_types=1);

namespace App\Domains\Forecast\Controller;

use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Forecast\Model\Forecast
     */
    protected ?Model $row;
}
