<?php declare(strict_types=1);

namespace App\Domains\Wallet\ControllerTest;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Exceptions\NotFoundException;

abstract class ControllerTestAbstract extends ControllerWebAbstract
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
        $this->row = Model::byId($id)->firstOr(static function () {
            throw new NotFoundException(__('wallet.error.not-found'));
        });
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    protected function rowLast(): Model
    {
        return $this->row = Model::orderByUpdatedAtDesc()->first();
    }
}
