<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use Illuminate\Support\Collection;
use App\Domains\Product\Model\Product as Model;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\Resource\Product as ProductResource;

class Sync extends ActionAbstract
{
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
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

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
        $this->products();
        $this->iterate();
        $this->update();
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
    protected function currencies(): void
    {
        $this->currencies = CurrencyModel::byPlatformId($this->platform->id)->get()->keyBy('code');
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $this->products = ProviderApiFactory::get($this->platform)->products(true);
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->products as $each) {
            $this->store($each);
        }
    }

    /**
     * @param \App\Services\Platform\Resource\Product $resource
     *
     * @return void
     */
    protected function store(ProductResource $resource): void
    {
        if (($currency_base = $this->currencies->get($resource->currencyBase)) === null) {
            return;
        }

        if (($currency_quote = $this->currencies->get($resource->currencyQuote)) === null) {
            return;
        }

        if (empty($row = $this->storeSearch($resource))) {
            $row = new Model();
        }

        $row->code = $resource->code;
        $row->name = ($currency_base->name.' / '.$currency_quote->name);
        $row->acronym = $resource->name;

        $row->precision = $resource->precision;

        $row->price_min = $resource->priceMin;
        $row->price_max = $resource->priceMax;
        $row->price_decimal = $resource->priceDecimal;

        $row->quantity_min = $resource->quantityMin;
        $row->quantity_max = $resource->quantityMax;
        $row->quantity_decimal = $resource->quantityDecimal;

        $row->crypto = true;
        $row->trade = true;
        $row->enabled = true;

        $row->currency_base_id = $currency_base->id;
        $row->currency_quote_id = $currency_quote->id;
        $row->platform_id = $this->platform->id;

        $row->save();
    }

    /**
     * @param \App\Services\Platform\Resource\Product $resource
     *
     * @return ?\App\Domains\Product\Model\Product
     */
    protected function storeSearch(ProductResource $resource): ?Model
    {
        return $this->current->get($resource->code);
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        Model::byPlatformId($this->platform->id)->byCodeIn($this->products->pluck('code')->toArray())->update([
            'trade' => true,
        ]);

        Model::byPlatformId($this->platform->id)->byCodeNotIn($this->products->pluck('code')->toArray())->update([
            'trade' => false,
        ]);
    }
}
