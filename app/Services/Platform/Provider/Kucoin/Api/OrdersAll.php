<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Provider\Kucoin\Api\Traits\OrderResource as OrderResourceTrait;

class OrdersAll extends ApiAbstract
{
    use OrderResourceTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->collection($this->query()->data->items);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestAuth('GET', '/api/v1/orders');
    }
}
