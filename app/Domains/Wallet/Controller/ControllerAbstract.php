<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Wallet\Model\Wallet
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return void
     */
    protected function row(int $id): void
    {
        $this->row = Model::byId($id)->byUserId($this->auth->id)->firstOr(static function () {
            throw new NotFoundException(__('wallet.error.not-found'));
        });
    }
}
