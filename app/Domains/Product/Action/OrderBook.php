<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use Illuminate\Support\Collection;
use App\Domains\Product\Model\Product as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Platform\Resource\OrderBook as OrderBookResource;

class OrderBook extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        $this->current();
        $this->api();
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function current(): void
    {
        $this->current = Model::byPlatformId($this->platform->id)->get()->keyBy('code');
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach (Model::byPlatformId($this->platform->id)->whereWalletsActive()->withExchange()->get() as $each) {
            $this->store($each, $this->api->orderBook($each->code));
        }
    }

    /**
     * @param \App\Domains\Product\Model\Product $row
     * @param \App\Services\Platform\Resource\OrderBook $resource
     *
     * @return void
     */
    protected function store(Model $row, OrderBookResource $resource): void
    {
        if (empty($row->exchange)) {
            return;
        }

        $min = $row->exchange->exchange * 0.95;
        $max = $row->exchange->exchange * 1.05;

        $asks = array_filter($resource->group('asks'), static fn ($value) => ($value < $max), ARRAY_FILTER_USE_KEY);
        $bids = array_filter($resource->group('bids'), static fn ($value) => ($value > $min), ARRAY_FILTER_USE_KEY);

        if ((count($asks) < 2) || (count($bids) < 2)) {
            return;
        }

        $row->ask_quantity = max($asks);
        $row->ask_price = array_search($row->ask_quantity, $asks);
        $row->ask_sum = array_sum($asks);

        $row->bid_quantity = max($bids);
        $row->bid_price = array_search($row->bid_quantity, $bids);
        $row->bid_sum = array_sum($bids);

        $row->save();
    }
}
