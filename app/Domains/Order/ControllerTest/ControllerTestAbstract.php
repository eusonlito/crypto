<?php declare(strict_types=1);

namespace App\Domains\Order\ControllerTest;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Exceptions\NotFoundException;

abstract class ControllerTestAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Order\Model\Order
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return void
     */
    protected function row(int $id): void
    {
        $this->row = Model::query()->byId($id)->firstOr(static function () {
            throw new NotFoundException(__('order.error.not-found'));
        });
    }

    /**
     * @return \App\Domains\Order\Model\Order
     */
    protected function rowLast(): Model
    {
        return $this->row = Model::query()->orderByUpdatedAtDesc()->first();
    }
}
