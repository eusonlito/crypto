<?php declare(strict_types=1);

namespace App\Domains\Currency\Action;

use Illuminate\Support\Collection;
use App\Domains\Currency\Model\Currency as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\Resource\Currency as CurrencyResource;

class Sync extends ActionAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @const array
     */
    protected const TRADE = ['BNB', 'BTC', 'BUSD', 'ETH', 'EUR', 'USD', 'USDC', 'USDT'];

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        $this->current();
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function current(): void
    {
        $this->current = Model::query()
            ->byPlatformId($this->platform->id)
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach (ProviderApiFactory::get($this->platform)->currencies() as $each) {
            $this->store($each);
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Currency $resource
     *
     * @return void
     */
    protected function store(CurrencyResource $resource): void
    {
        if ($this->storeSearch($resource)) {
            return;
        }

        Model::query()->insert([
            'code' => $resource->code,
            'name' => $resource->name,
            'symbol' => $resource->symbol,
            'precision' => $resource->precision,
            'trade' => in_array($resource->code, static::TRADE),
            'platform_id' => $this->platform->id,
        ]);
    }

    /**
     * @param \App\Services\Platform\Resource\Currency $resource
     *
     * @return bool
     */
    protected function storeSearch(CurrencyResource $resource): bool
    {
        return $this->current->has($resource->code);
    }
}
