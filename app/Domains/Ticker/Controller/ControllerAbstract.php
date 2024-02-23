<?php declare(strict_types=1);

namespace App\Domains\Ticker\Controller;

use App\Domains\Ticker\Model\Ticker as Model;
use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Exceptions\NotFoundException;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Ticker\Model\Ticker
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
            throw new NotFoundException(__('ticker.error.not-found'));
        });
    }
}
