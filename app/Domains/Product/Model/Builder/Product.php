<?php declare(strict_types=1);

namespace App\Domains\Product\Model\Builder;

use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Product\Model\ProductUser as ProductUserModel;
use App\Domains\Core\Model\Builder\BuilderAbstract;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Product extends BuilderAbstract
{
    /**
     * @param array $codes
     *
     * @return self
     */
    public function byCodeIn(array $codes): self
    {
        return $this->whereIn('code', $codes);
    }

    /**
     * @param array $codes
     *
     * @return self
     */
    public function byCodeNotIn(array $codes): self
    {
        return $this->whereNotIn('code', $codes);
    }

    /**
     * @param int $platform_id
     *
     * @return self
     */
    public function byPlatformId(int $platform_id): self
    {
        return $this->where($this->addTable('platform_id'), $platform_id);
    }

    /**
     * @param int $currency_base_id
     *
     * @return self
     */
    public function byCurrencyBaseId(int $currency_base_id): self
    {
        return $this->where('currency_base_id', $currency_base_id);
    }

    /**
     * @param int $currency_base_id
     * @param int $currency_quote_id
     *
     * @return self
     */
    public function byCurrencyBaseIdAndCurrencyQuoteId(int $currency_base_id, int $currency_quote_id): self
    {
        return $this->byCurrencyBaseId($currency_base_id)->byCurrencyQuoteId($currency_quote_id);
    }

    /**
     * @param int $currency_quote_id
     *
     * @return self
     */
    public function byCurrencyQuoteId(int $currency_quote_id): self
    {
        return $this->where('currency_quote_id', $currency_quote_id);
    }

    /**
     * @param array $currency_quote_ids
     *
     * @return self
     */
    public function byCurrencyQuoteIds(array $currency_quote_ids): self
    {
        return $this->whereIntegerInRaw('currency_quote_id', $currency_quote_ids);
    }

    /**
     * @param int $currency_id
     *
     * @return self
     */
    public function byCurrencyTradeAllowed(int $currency_id): self
    {
        return $this->where('trade', true)
            ->where(static function ($q) use ($currency_id) {
                $q->where(static function ($q) use ($currency_id) {
                    $q->byCurrencyBaseId($currency_id)->whereCurrencyQuoteTrade();
                })->orWhere('currency_quote_id', $currency_id);
            });
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->enabled()->orderBy('code', 'ASC');
    }

    /**
     * @param ?int $currency_quote_id
     *
     * @return self
     */
    public function whenCurrencyQuoteId(?int $currency_quote_id): self
    {
        return $this->when($currency_quote_id, static fn ($q) => $q->byCurrencyQuoteId($currency_quote_id));
    }

    /**
     * @param ?int $platform_id
     *
     * @return self
     */
    public function whenPlatformId(?int $platform_id): self
    {
        return $this->when($platform_id, static fn ($q) => $q->byPlatformId($platform_id));
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
    public function whereCurrencyQuoteTrade(): self
    {
        return $this->whereIn('currency_quote_id', CurrencyModel::query()->select('id')->where('trade', true));
    }

    /**
     * @return self
     */
    public function whereFavorite(): self
    {
        return $this->whereIn('id', ProductUserModel::query()->select('product_id')->whereFavorite());
    }

    /**
     * @return self
     */
    public function whereFiat(): self
    {
        return $this->whereTracking(false)
            ->whereTrade(false)
            ->whereCrypto(false)
            ->whereColumn('currency_quote_id', 'currency_base_id');
    }

    /**
     * @param bool $tracking = true
     *
     * @return self
     */
    public function whereTracking(bool $tracking = true): self
    {
        return $this->where('tracking', $tracking);
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
     * @param int $user_id
     *
     * @return self
     */
    public function whereUserPivotFavoriteByUserId(int $user_id): self
    {
        return $this->whereIn('id', ProductUserModel::query()->select('product_id')->byUserId($user_id)->whereFavorite());
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function whereWalletsByUserId(int $user_id): self
    {
        return $this->whereIn('id', WalletModel::query()->select('product_id')->byUserId($user_id)->whereCrypto());
    }

    /**
     * @return self
     */
    public function whereWallet(): self
    {
        return $this->whereIn('id', WalletModel::query()->select('product_id')->whereCrypto());
    }

    /**
     * @return self
     */
    public function whereWalletOrFavorite(): self
    {
        return $this->where(
            static fn ($q) => $q->orWhere(
                static fn ($q) => $q->whereIn('id', WalletModel::query()->select('product_id')->whereCrypto())
            )->orWhere(
                static fn ($q) => $q->whereIn('id', ProductUserModel::query()->select('product_id')->whereFavorite())
            ),
        );
    }

    /**
     * @return self
     */
    public function whereWalletsActive(): self
    {
        return $this->whereIn('id', WalletModel::query()->select('product_id')->enabled()->withAmount()->whereCrypto());
    }

    /**
     * @return self
     */
    public function withCurrencyBase(): self
    {
        return $this->with(['currencyBase']);
    }

    /**
     * @return self
     */
    public function withCurrencies(): self
    {
        return $this->with(['currencyBase', 'currencyQuote']);
    }

    /**
     * @return self
     */
    public function withExchange(): self
    {
        return $this->with(['exchange']);
    }

    /**
     * @param int $time
     * @param ?string $start_at = null
     * @param ?string $end_at = null
     * @param bool $detail = false
     *
     * @return self
     */
    public function withExchangesChart(int $time, ?string $start_at = null, ?string $end_at = null, bool $detail = false): self
    {
        return $this->with(['exchanges' => static fn ($q) => $q->chart($time, $start_at, $end_at, $detail)]);
    }

    /**
     * @return self
     */
    public function withExchangesVariance(): self
    {
        return $this->with(['exchanges' => static fn ($q) => $q->variance()]);
    }

    /**
     * @return self
     */
    public function withPlatform(): self
    {
        return $this->with(['platform']);
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function withPlatformAndPivot(int $user_id): self
    {
        return $this->with(['platform' => static fn ($q) => $q->withUserPivot($user_id)]);
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function withUserPivotFavoriteByUserId(int $user_id): self
    {
        return $this->with(['userPivot' => static fn ($q) => $q->byUserId($user_id)->whereFavorite()]);
    }
}
