<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\JsonResponse;

class UpdateColumn extends ControllerAbstract
{
    /**
     * @param int $id
     * @param string $column
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $id, string $column): JsonResponse
    {
        $this->row($id);

        return $this->json($this->factory()->fractal('column', $this->action()->updateColumn(), $column));
    }
}
