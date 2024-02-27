<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use Illuminate\Support\Collection;
use App\Domains\Product\Model\Product as Model;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;

class Fiat extends ActionAbstract
{
    /**
     * @const
     */
    protected const CURRENCIES = ['EUR', 'USD', 'USDC', 'USDT', 'BUSD'];

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $current;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $currencies;

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
        $this->currencies();
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
    protected function currencies(): void
    {
        $this->currencies = CurrencyModel::query()
            ->byPlatformId($this->platform->id)
            ->byCodes(static::CURRENCIES)
            ->get()
            ->keyBy('code');
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->currencies as $each) {
            if ($row = $this->store($each)) {
                $this->exchange($row);
            }
        }
    }

    /**
     * @param \App\Domains\Currency\Model\Currency $currency
     *
     * @return ?\App\Domains\Product\Model\Product
     */
    protected function store(CurrencyModel $currency): ?Model
    {
        if ($this->storeSearch($currency)) {
            return null;
        }

        return Model::query()->create([
            'code' => $currency->code,
            'name' => $currency->name,
            'acronym' => $currency->code,
            'crypto' => false,
            'trade' => false,
            'enabled' => true,
            'currency_base_id' => $currency->id,
            'currency_quote_id' => $currency->id,
            'platform_id' => $this->platform->id,
        ]);
    }

    /**
     * @param \App\Domains\Currency\Model\Currency $currency
     *
     * @return bool
     */
    protected function storeSearch(CurrencyModel $currency): bool
    {
        return $this->current->has($currency->code);
    }

    /**
     * @param \App\Domains\Product\Model\Product $row
     *
     * @return void
     */
    protected function exchange(Model $row): void
    {
        ExchangeModel::query()->insert([
            'exchange' => 1,
            'platform_id' => $this->platform->id,
            'product_id' => $row->id,
        ]);
    }
}
