<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Wallet\Model\Wallet as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Wallet\Model\Wallet
     */
    protected ?Model $row;
}
