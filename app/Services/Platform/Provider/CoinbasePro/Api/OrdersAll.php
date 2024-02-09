<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Illuminate\Support\Collection;
use App\Services\Platform\Provider\CoinbasePro\Api\Traits\OrderResource as OrderResourceTrait;

class OrdersAll extends ApiAbstract
{
    use OrderResourceTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->collection($this->query());
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/orders', ['status' => 'all']);
    }
}
