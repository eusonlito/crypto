<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\JsonResponse;
use App\Domains\User\Model\User as Model;

class UpdateBoolean extends ControllerAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->row($id);

        return $this->json($this->factory()->fractal('simple', $this->action()->updateBoolean()));
    }

    /**
     * @param int $id
     *
     * @return void
     */
    protected function row(int $id): void
    {
        $this->row = Model::byId($id)->firstOr(static function () {
            throw new NotFoundException(__('user-update-boolean.error.not-found'));
        });
    }
}
