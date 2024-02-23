<?php declare(strict_types=1);

namespace App\Domains\Wallet\Fractal;

use App\Domains\Core\Fractal\FractalAbstract;
use App\Domains\Wallet\Model\Wallet as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return array
     */
    protected function simple(Model $row): array
    {
        return $row->only('id', 'name', 'buy_stop', 'sell_stop', 'visible', 'enabled');
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param string $column
     *
     * @return array
     */
    protected function column(Model $row, string $column): array
    {
        return $row->only('id', $column);
    }
}
