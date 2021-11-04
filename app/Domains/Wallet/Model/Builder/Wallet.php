<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model\Builder;

use App\Domains\Shared\Model\Builder\BuilderAbstract;

class Wallet extends BuilderAbstract
{
    /**
     * @param string $address
     *
     * @return self
     */
    public function byAddress(string $address): self
    {
        return $this->where('address', $address);
    }

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
     * @param bool $custom = true
     *
     * @return self
     */
    public function whereCustom(bool $custom = true): self
    {
        return $this->where('custom', $custom);
    }

    /**
     * @param bool $crypto = true
     *
     * @return self
     */
    public function whereCrypto(bool $crypto = true): self
    {
        return $this->where('crypto', $crypto);
    }

    /**
     * @return self
     */
    public function whereBuyOrSellPending(): self
    {
        return $this->enabled()->where(static function ($q) {
            $q->whereNotNull('buy_stop_min_at')->orWhereNotNull('sell_stop_max_at');
        });
    }

    /**
     * @return self
     */
    public function whereBuyStopMaxActivated(): self
    {
        return $this->enabled()
            ->where('processing', false)
            ->where('crypto', true)
            ->where('buy_stop', true)
            ->where('buy_stop_amount', '>', 0)
            ->where('buy_stop_min', '>', 0)
            ->where('buy_stop_max', '>', 0)
            ->where('buy_stop_max_executable', true)
            ->whereNotNull('buy_stop_min_at')
            ->whereNotNull('buy_stop_max_at');
    }

    /**
     * @return self
     */
    public function whereBuyStopMinActivated(): self
    {
        return $this->enabled()
            ->where('processing', false)
            ->where('crypto', true)
            ->where('buy_stop', true)
            ->where('buy_stop_amount', '>', 0)
            ->where('buy_stop_min', '>', 0)
            ->where('buy_stop_min_executable', true)
            ->where('buy_stop_max', '>', 0)
            ->whereNotNull('buy_stop_min_at')
            ->whereNull('buy_stop_max_at');
    }

    /**
     * @return self
     */
    public function whereSellStopMaxActivated(): self
    {
        return $this->enabled()
            ->where('processing', false)
            ->where('crypto', true)
            ->where('amount', '>', 0)
            ->where('sell_stop', true)
            ->where('sell_stop_amount', '>', 0)
            ->where('sell_stop_min', '>', 0)
            ->where('sell_stop_max', '>', 0)
            ->where('sell_stop_max_executable', true)
            ->whereNull('sell_stop_min_at')
            ->whereNotNull('sell_stop_max_at');
    }

    /**
     * @return self
     */
    public function whereSellStopMinActivated(): self
    {
        return $this->enabled()
            ->where('processing', false)
            ->where('crypto', true)
            ->where('amount', '>', 0)
            ->where('sell_stop', true)
            ->where('sell_stop_amount', '>', 0)
            ->where('sell_stop_min', '>', 0)
            ->where('sell_stop_max', '>', 0)
            ->where('sell_stop_min_executable', true)
            ->whereNotNull('sell_stop_min_at')
            ->whereNotNull('sell_stop_max_at');
    }

    /**
     * @return self
     */
    public function whereSellStopLossActivated(): self
    {
        return $this->enabled()
            ->where('processing', false)
            ->where('crypto', true)
            ->where('amount', '>', 0)
            ->where('sell_stoploss', true)
            ->where('sell_stoploss_exchange', '>', 0)
            ->where('sell_stoploss_executable', true)
            ->whereNotNull('sell_stoploss_at');
    }

    /**
     * @param bool $trade = true
     *
     * @return self
     */
    public function whereTrade(bool $trade = true): self
    {
        return $this->where('trade', $trade);
    }

    /**
     * @param bool $visible = true
     *
     * @return self
     */
    public function whereVisible(bool $visible = true): self
    {
        return $this->where('visible', $visible);
    }

    /**
     * @return self
     */
    public function withAmount(): self
    {
        return $this->where('amount', '>', 0);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->with(['currency', 'platform', 'product'])
            ->orderByRaw('`order` = 0 ASC')
            ->orderBy('order', 'ASC')
            ->orderBy('current_value', 'DESC')
            ->orderBy('name', 'ASC');
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

    /**
     * @return self
     */
    public function groupByProductId(): self
    {
        return $this->groupBy(['product_id']);
    }

    /**
     * @return self
     */
    public function listSelect(): self
    {
        return $this->with(['platform'])->orderBy('platform_id', 'ASC')->orderBy('name', 'ASC');
    }
}
