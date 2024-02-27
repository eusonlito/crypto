<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerWebAbstract
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
        $this->row = Model::query()->byId($id)->byUserId($this->auth->id)->where('custom', true)->firstOr(static function () {
            throw new NotFoundException(__('order.error.not-found'));
        });
    }
}
