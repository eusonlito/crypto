<?php declare(strict_types=1);

namespace App\Domains\Exchange\Model\Builder;

use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Shared\Model\Builder\BuilderAbstract;

class Exchange extends BuilderAbstract
{
    /**
     * @return self
     */
    public function withPlatform(): self
    {
        return $this->with(['platform']);
    }

    /**
     * @return self
     */
    public function withProduct(): self
    {
        return $this->with(['product']);
    }

    /**
     * @param int $platform_id
     *
     * @return self
     */
    public function byPlatformId(int $platform_id): self
    {
        return $this->where('platform_id', $platform_id);
    }

    /**
     * @param int $product_id
     *
     * @return self
     */
    public function byProductId(int $product_id): self
    {
        return $this->where('product_id', $product_id);
    }

    /**
     * @param array $product_ids
     *
     * @return self
     */
    public function byProductIds(array $product_ids): self
    {
        return $this->whereIn('product_id', $product_ids);
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function afterDate(string $date): self
    {
        return $this->where('created_at', '>=', $date);
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function beforeDate(string $date): self
    {
        return $this->where('created_at', '<=', $date);
    }

    /**
     * @return self
     */
    public function orderByCreated(): self
    {
        return $this->orderBy('created_at', 'DESC');
    }

    /**
     * @return self
     */
    public function lastByProduct(): self
    {
        $date = date('Y-m-d H:i:s', strtotime('-1 hour'));

        return $this->afterDate($date)->whereIn('id', Model::selectRaw('MAX(id)')->afterDate($date)->groupByProductId());
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function lastByProductBeforDate(string $date): self
    {
        return $this->afterDate($date)->whereIn('id', Model::selectRaw('MIN(id)')->afterDate($date)->groupByProductId());
    }

    /**
     * @param int $minutes = 60
     *
     * @return self
     */
    public function chart(int $minutes = 60): self
    {
        return $this->selectMaxMinutes()
            ->afterDate(date('Y-m-d H:i:s', strtotime('-'.$minutes.' minutes')))
            ->groupByMinutesProduct($minutes);
    }

    /**
     * @return self
     */
    public function selectAvg(): self
    {
        return $this->selectRaw(trim('
            MAX(`id`) `id`, AVG(`exchange`) `exchange`, MAX(`product_id`) `product_id`
        '));
    }

    /**
     * @return self
     */
    public function selectMaxMinutes(): self
    {
        return $this->selectRaw(trim('
            MAX(`id`) `id`, MAX(`exchange`) `exchange`, MAX(`created_at`) `created_at`, `product_id`
        '));
    }

    /**
     * @return self
     */
    public function groupByProductId(): self
    {
        return $this->groupBy('product_id');
    }

    /**
     * @param int $minutes = 60
     *
     * @return self
     */
    public function groupByMinutesProduct(int $minutes = 60): self
    {
        return $this->groupByRaw(sprintf('UNIX_TIMESTAMP(`created_at`) DIV %d, `product_id`', 300 * $minutes / 60 / 12));
    }
}
