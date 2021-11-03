<?php declare(strict_types=1);

namespace App\Domains\Product\Controller;

use Illuminate\Http\JsonResponse;

class Favorite extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->row($id);

        return $this->json($this->factory()->fractal('simple', $this->action()->favorite()));
    }
}
