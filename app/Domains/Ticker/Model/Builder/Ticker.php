<?php declare(strict_types=1);

namespace App\Domains\Ticker\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class Ticker extends BuilderAbstract
{
    /**
     * @param int $currency_id
     *
     * @return self
     */
    public function byCurrencyId(int $currency_id): self
    {
        return $this->where('currency_id', $currency_id);
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
     * @param int $platform_id
     *
     * @return self
     */
    public function byPlatformId(int $platform_id): self
    {
        return $this->where('platform_id', $platform_id);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->with(['currency', 'platform', 'product'])
            ->orderBy('value_current', 'DESC');
    }

    /**
     * @return self
     */
    public function withCurrency(): self
    {
        return $this->with(['currency']);
    }

    /**
     * @param int $time
     *
     * @return self
     */
    public function withExchangesChart(int $time): self
    {
        return $this->with(['exchanges' => fn ($q) => $q->chart($time)]);
    }

    /**
     * @return self
     */
    public function withPlatform(): self
    {
        return $this->with(['platform']);
    }
}
