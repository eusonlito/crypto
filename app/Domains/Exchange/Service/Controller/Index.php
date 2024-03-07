<?php declare(strict_types=1);

namespace App\Domains\Exchange\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class Index extends ControllerAbstract
{
    /**
     * @const
     */
    protected const SORT_VALUES = ['percent', 'product.name', 'exchange', 'previous_exchange', 'difference'];

    /**
     * @const
     */
    protected const TOP_VALUES = ['50', '100', 'all'];

    /**
     * @var int
     */
    protected int $top;

    /**
     * @var string
     */
    protected string $time;

    /**
     * @var string
     */
    protected string $datetime;

    /**
     * @var string
     */
    protected string $sort;

    /**
     * @var string
     */
    protected string $sortMode;

    /**
     * @var ?int
     */
    protected ?int $platformId;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $platforms;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $products;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     *
     * @return self
     */
    public function __construct(protected Request $request, protected Authenticatable $auth)
    {
        $this->filters();
        $this->options();
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'top' => $this->auth->preference('exchange-index-top', $this->request->input('top'), '50'),
            'time' => (int)$this->auth->preference('exchange-index-time', $this->request->input('time'), 60),
            'platform_id' => (int)$this->auth->preference('exchange-index-platform_id', $this->request->input('platform_id'), 0),
        ]);
    }

    /**
     * @return self
     */
    protected function options(): self
    {
        $this->top($this->request->input('top'));
        $this->time($this->requestInteger('time'));
        $this->sort($this->request->input('sort'));
        $this->sortMode($this->request->input('sort_mode'));
        $this->platformId($this->request->input('platform_id'));

        return $this;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'list' => $this->list(),
            'platforms' => $this->platforms(),
            'filters' => $this->request->input(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return $this->relations($this->calculate());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function platforms(): Collection
    {
        return $this->cache(fn () => PlatformModel::query()->list()->get());
    }

    /**
     * @param ?string $top
     *
     * @return self
     */
    protected function top(?string $top): self
    {
        $this->top = intval(in_array($top, static::TOP_VALUES) ? $top : static::TOP_VALUES[0]);

        return $this;
    }

    /**
     * @param ?int $time
     *
     * @return self
     */
    protected function time(?int $time): self
    {
        $this->datetime = date('Y-m-d H:i:s', strtotime('-'.($time ?: 12).' minutes'));

        return $this;
    }

    /**
     * @param ?string $sort
     *
     * @return self
     */
    protected function sort(?string $sort): self
    {
        $this->sort = in_array($sort, static::SORT_VALUES) ? $sort : static::SORT_VALUES[0];

        return $this;
    }

    /**
     * @param ?string $sort_mode
     *
     * @return self
     */
    protected function sortMode(?string $sort_mode): self
    {
        $this->sortMode = (strtolower((string)$sort_mode) === 'asc') ? 'asc' : 'desc';

        return $this;
    }

    /**
     * @param ?int $platform_id
     *
     * @return self
     */
    protected function platformId(?int $platform_id): self
    {
        $this->platformId = $platform_id ?: null;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function last(): Collection
    {
        $list = Model::query()->lastByProduct();

        if ($this->platformId) {
            $list->byPlatformId($this->platformId);
        }

        return $list->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function previous(): Collection
    {
        $list = Model::query()->lastByProductBeforDate($this->datetime);

        if ($this->platformId) {
            $list->byPlatformId($this->platformId);
        }

        return $list->get()->keyBy('product_id');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function calculate(): Collection
    {
        $previous = $this->previous();

        return $this->last()
            ->map(fn ($row) => $this->calculateRow($row, $previous->get($row->product_id)))
            ->sortBy([[$this->sort, $this->sortMode]])
            ->slice(0, $this->top ?: $previous->count());
    }

    /**
     * @param \App\Domains\Exchange\Model\Exchange $last
     * @param ?\App\Domains\Exchange\Model\Exchange $previous
     *
     * @return \App\Domains\Exchange\Model\Exchange
     */
    protected function calculateRow(Model $last, ?Model $previous): Model
    {
        if ($previous) {
            $last->previous_exchange = $previous->exchange;
            $last->difference = $last->exchange - $previous->exchange;
            $last->percent = round(100 - ($previous->exchange * 100 / $last->exchange), 2);
        } else {
            $last->previous_exchange = 0;
            $last->difference = 0;
            $last->percent = 0;
        }

        return $last;
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return \Illuminate\Support\Collection
     */
    protected function relations(Collection $list): Collection
    {
        $platforms = $this->platforms()->keyBy('id');

        if ($this->top) {
            $products = ProductModel::query()->byIds($list->pluck('product_id')->toArray())->get()->keyBy('id');
        } else {
            $products = ProductModel::query()->get()->keyBy('id');
        }

        foreach ($list as $each) {
            $each->setRelation('platform', $platforms->get($each->platform_id));
            $each->setRelation('product', $products->get($each->product_id));
        }

        return $list;
    }
}
