<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Core\Service\Controller\ControllerAbstract as ControllerAbstractCore;

abstract class ControllerAbstract extends ControllerAbstractCore
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected Authenticatable $auth;
}
