<?php declare(strict_types=1);

namespace App\Domains\Product\Model\Builder;

use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Product\Model\ProductUser as ProductUserModel;
use App\Domains\Shared\Model\Builder\BuilderAbstract;
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
        return $this->where('platform_id', $platform_id);
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
     * @param int $currency_quote_id
     *
     * @return self
     */
    public function byCurrencyQuoteId(int $currency_quote_id): self
    {
        return $this->where('currency_quote_id', $currency_quote_id);
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
        return $this->whereIn('currency_quote_id', CurrencyModel::select('id')->where('trade', true));
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
        return $this->whereIn('id', ProductUserModel::select('product_id')->byUserId($user_id)->whereFavorite());
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function whereWalletsByUserId(int $user_id): self
    {
        return $this->whereIn('id', WalletModel::select('product_id')->byUserId($user_id)->whereCrypto());
    }

    /**
     * @return self
     */
    public function whereWalletsActive(): self
    {
        return $this->whereIn('id', WalletModel::select('product_id')->enabled()->withAmount()->whereCrypto());
    }

    /**
     * @return self
     */
    public function whereWalletsInactive(): self
    {
        return $this->whereNotIn('id', WalletModel::select('product_id')->enabled()->withAmount()->whereCrypto());
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
     *
     * @return self
     */
    public function withExchangesChart(int $time, ?string $start_at = null, ?string $end_at = null): self
    {
        return $this->with(['exchanges' => static fn ($q) => $q->chart($time, $start_at, $end_at)]);
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

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderBy('code', 'ASC');
    }
}
