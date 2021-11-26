<?php declare(strict_types=1);

namespace App\Domains\Exchange\Service\Controller;

use Illuminate\Support\Collection;
use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class Index
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
     * @param array $options = []
     *
     * @return self
     */
    public function __construct(array $options = [])
    {
        $this->options($options);
    }

    /**
     * @param array $options
     *
     * @return self
     */
    public function options(array $options): self
    {
        $this->top($options['top'] ?? null);
        $this->time($options['time'] ?? null);
        $this->sort($options['sort'] ?? null);
        $this->sortMode($options['sort_mode'] ?? null);
        $this->platformId($options['platform_id'] ?? null);

        return $this;
    }

    /**
     * @param ?string $top
     *
     * @return self
     */
    public function top(?string $top): self
    {
        $this->top = intval(in_array($top, static::TOP_VALUES) ? $top : static::TOP_VALUES[0]);

        return $this;
    }

    /**
     * @param ?int $time
     *
     * @return self
     */
    public function time(?int $time): self
    {
        $this->datetime = date('Y-m-d H:i:s', strtotime('-'.($time ?: 12).' minutes'));

        return $this;
    }

    /**
     * @param ?string $sort
     *
     * @return self
     */
    public function sort(?string $sort): self
    {
        $this->sort = in_array($sort, static::SORT_VALUES) ? $sort : static::SORT_VALUES[0];

        return $this;
    }

    /**
     * @param ?string $sort_mode
     *
     * @return self
     */
    public function sortMode(?string $sort_mode): self
    {
        $this->sortMode = (strtolower((string)$sort_mode) === 'asc') ? 'asc' : 'desc';

        return $this;
    }

    /**
     * @param ?int $platform_id
     *
     * @return self
     */
    public function platformId(?int $platform_id): self
    {
        $this->platformId = $platform_id ?: null;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        return $this->relations($this->calculate());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function last(): Collection
    {
        $list = Model::lastByProduct();

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
        $list = Model::lastByProductBeforDate($this->datetime);

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
        $platforms = PlatformModel::get()->keyBy('id');

        if ($this->top) {
            $products = ProductModel::byIds($list->pluck('product_id')->toArray())->get()->keyBy('id');
        } else {
            $products = ProductModel::get()->keyBy('id');
        }

        foreach ($list as $each) {
            $each->setRelation('platform', $platforms->get($each->platform_id));
            $each->setRelation('product', $products->get($each->product_id));
        }

        return $list;
    }
}
