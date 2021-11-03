<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use Illuminate\Support\Collection;

class OrdersAll extends ApiAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return collect();
    }
}
