<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use App\Domains\Shared\Controller\ControllerWebAbstract;
use App\Domains\Wallet\Model\Wallet as WalletModel;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @return bool
     */
    protected function hasWallets(): bool
    {
        return (bool)WalletModel::byUserId($this->auth->id)->limit(1)->count();
    }
}
