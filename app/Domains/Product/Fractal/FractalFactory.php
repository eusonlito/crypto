<?php declare(strict_types=1);

namespace App\Domains\Product\Fractal;

use App\Domains\Shared\Fractal\FractalAbstract;
use App\Domains\Product\Model\Product as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\Product\Model\Product $row
     *
     * @return array
     */
    protected function simple(Model $row): array
    {
        return $row->only('id', 'name', 'tracking', 'enabled');
    }
}
