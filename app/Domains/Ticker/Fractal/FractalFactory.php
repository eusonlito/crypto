<?php declare(strict_types=1);

namespace App\Domains\Ticker\Fractal;

use App\Domains\Shared\Fractal\FractalAbstract;
use App\Domains\Ticker\Model\Ticker as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\Ticker\Model\Ticker $row
     *
     * @return array
     */
    protected function simple(Model $row): array
    {
        return $row->only('id', 'enabled');
    }
}
