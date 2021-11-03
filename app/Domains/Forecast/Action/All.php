<?php declare(strict_types=1);

namespace App\Domains\Forecast\Action;

use Illuminate\Support\Collection;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Forecast\Service\Version\VersionValuesFactory;
use App\Domains\Product\Model\Product as ProductModel;

class All extends ActionAbstract
{
    /**
     * @var \App\Domains\Currency\Model\Currency
     */
    protected CurrencyModel $currency;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $list;

    /**
     * @param \App\Domains\Currency\Model\Currency $currency
     *
     * @return \Illuminate\Support\Collection
     */
    public function handle(CurrencyModel $currency): Collection
    {
        $this->currency = $currency;

        $this->products();
        $this->iterate();

        return $this->list;
    }

    /**
     * @return void
     */
    protected function products(): void
    {
        $q = ProductModel::byCurrencyTradeAllowed($this->currency->id)->withCurrencies();

        if ($this->data['favorite']) {
            $q->whereUserPivotFavoriteByUserId($this->auth->id);
        }

        $this->products = $q->get();
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        $this->list = collect();

        foreach ($this->products as $each) {
            $this->iterateProduct($each);
        }
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return void
     */
    protected function iterateProduct(ProductModel $product): void
    {
        if ($row = $this->iterateProductRow($product, $this->iterateProductExchanges($product))) {
            $this->list->push($row);
        }
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return \Illuminate\Support\Collection
     */
    protected function iterateProductExchanges(ProductModel $product): Collection
    {
        return ExchangeModel::byProductId($product->id)
            ->afterDate(date('Y-m-d H:i:s', strtotime('-1 week')))
            ->orderByFirst()
            ->pluck('exchange', 'created_at');
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return ?\App\Domains\Forecast\Model\Forecast
     */
    protected function iterateProductRow(ProductModel $product, Collection $exchanges): ?Model
    {
        $version = VersionValuesFactory::get($exchanges);

        if ($version->error()) {
            return null;
        }

        $row = new Model();

        $row->version = $version->version();
        $row->keys = $version->keys();
        $row->values = $version->values();
        $row->valid = $version->valid();

        $row->side = ($this->currency->id === $product->currency_base_id) ? 'sell' : 'buy';

        $row->platform_id = $this->currency->platform_id;
        $row->product_id = $product->id;
        $row->user_id = $this->auth->id;

        $row->setRelation('product', $product);

        return $row;
    }
}
