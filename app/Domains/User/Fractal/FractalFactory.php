<?php declare(strict_types=1);

namespace App\Domains\User\Fractal;

use App\Domains\Core\Fractal\FractalAbstract;
use App\Domains\User\Model\User as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\User\Model\User $row
     *
     * @return array
     */
    protected function simple(Model $row): array
    {
        return $row->only('id', 'email', 'enabled', 'admin');
    }

    /**
     * @param \App\Domains\User\Model\User $row
     * @param string $column
     *
     * @return array
     */
    protected function column(Model $row, string $column): array
    {
        return $row->only('id', $column);
    }
}
