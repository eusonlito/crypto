<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Illuminate\Support\Collection;

class Exchanges extends ApiAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return collect();
    }
}
