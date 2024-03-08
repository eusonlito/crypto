<?php declare(strict_types=1);

namespace App\Domains\Exchange\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class Variance extends ControllerAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $platforms;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @var array
     */
    protected array $dates;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     *
     * @return self
     */
    public function __construct(protected Request $request, protected Authenticatable $auth)
    {
        $this->request();
        $this->dates();
    }

    /**
     * @return void
     */
    protected function request(): void
    {
        $this->request->merge([
            'platform_id' => (int)$this->auth->preference('exchange-variance-platform_id', $this->request->input('platform_id'), 0),
        ]);
    }

    /**
     * @return void
     */
    protected function dates(): void
    {
        $this->dates = [
            '2_days' => date('Y-m-d H:i:s', strtotime('-2 days')),
            '1_day' => date('Y-m-d H:i:s', strtotime('-1 day')),
            '12_hours' => date('Y-m-d H:i:s', strtotime('-12 hours')),
            '6_hours' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            '4_hours' => date('Y-m-d H:i:s', strtotime('-4 hours')),
            '2_hours' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            '1_hour' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            '30_minutes' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
            '10_minutes' => date('Y-m-d H:i:s', strtotime('-10 minutes')),
            '5_minutes' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
            'last' => date('Y-m-d H:i:s', strtotime('-10 seconds')),
        ];
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'platforms' => $this->platforms(),
            'products' => $this->products(),
            'filters' => $this->filters(),
            'list' => $this->list(),
            'dates' => $this->dates,
        ];
    }

    /**
     * @return array
     */
    protected function filters(): array
    {
        return [
            'currency_quote_id' => $this->requestInteger('currency_quote_id'),
            'platform_id' => $this->requestInteger('platform_id'),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function platforms(): Collection
    {
        return $this->cache(fn () => PlatformModel::query()->list()->get());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function products(): Collection
    {
        return $this->cache(
            fn () => ProductModel::query()
                ->whereFiat()
                ->whenPlatformId($this->requestInteger('platform_id'))
                ->list()
                ->get()
        );
    }

    /**
     * @return array
     */
    protected function currencyQuoteIds(): array
    {
        return $this->cache(
            fn () => $this->products()->pluck('currency_quote_id')->all()
        );
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return ProductModel::query()
            ->select('id', 'code', 'acronym', 'name')
            ->byCurrencyQuoteIds($this->currencyQuoteIds())
            ->whenCurrencyQuoteId($this->requestInteger('currency_quote_id'))
            ->whenPlatformId($this->requestInteger('platform_id'))
            ->enabled()
            ->get()
            ->keyBy('id')
            ->tap($this->listExchanges(...))
            ->filter($this->listFilter(...))
            ->map($this->listCalculate(...))
            ->sort($this->listSort(...));
    }

    /**
     * @param \Illuminate\Support\Collection $products
     *
     * @return \Illuminate\Support\Collection
     */
    protected function listExchanges(Collection $products): Collection
    {
        $product_ids = $products->keys()->all();
        $values = [];

        foreach ($this->dates as $code => $date) {
            foreach ($this->listExchangesByDate($product_ids, $date) as $exchange) {
                $values[$exchange->product_id][$code] = $exchange->exchange;
            }
        }

        foreach ($products as $product) {
            $product->values = $values[$product->id] ?? null;
        }

        return $products;
    }

    /**
     * @param array $product_ids
     * @param string $date
     *
     * @return \Illuminate\Support\Collection
     */
    protected function listExchangesByDate(array $product_ids, string $date): Collection
    {
        return Model::query()
                ->byProductIds($product_ids)
                ->lastByProductBeforDate($date)
                ->get();
    }

    /**
     * @return bool
     */
    protected function listFilter(ProductModel $product): bool
    {
        return boolval($product->values);
    }

    /**
     * @return \App\Domains\Product\Model\Product
     */
    protected function listCalculate(ProductModel $product): ProductModel
    {
        $previous = null;
        $percents = [];

        foreach ($product->values as $code => $value) {
            $percents[$code] = $previous ? helper()->percent($previous, $value) : 0;
            $previous = $value;
        }

        $product->percents = $percents;

        return $product;
    }

    /**
     * @param \App\Domains\Product\Model\Product $a
     * @param \App\Domains\Product\Model\Product $b
     *
     * @return int
     */
    protected function listSort(ProductModel $a, ProductModel $b): int
    {
        return (array_sum($a->percents) > array_sum($b->percents)) ? -1 : 1;
    }
}