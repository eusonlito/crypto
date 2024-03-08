<?php declare(strict_types=1);

namespace App\Domains\Product\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Builder\Product as Builder;

class Product extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'product';

    /**
     * @const string
     */
    public const TABLE = 'product';

    /**
     * @const string
     */
    public const FOREIGN = 'product_id';

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->whereIn('product.platform_id', PlatformModel::query()->select('id'));
        });
    }

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Product\Model\Builder\Product
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyBase(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'currency_base_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyQuote(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, 'currency_quote_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function exchange(): HasOne
    {
        return $this->hasOne(ExchangeModel::class, static::FOREIGN)
            ->ofMany('id', 'max', 'relation')
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-30 minutes')));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(ExchangeModel::class, static::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(PlatformModel::class, PlatformModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userPivot(): HasOne
    {
        return $this->hasOne(ProductUser::class, static::FOREIGN);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->acronym.$this->currencyTitle(' - ');
    }

    /**
     * @param string $prefix = ''
     *
     * @return string
     */
    public function currencyTitle(string $prefix = ''): string
    {
        return $prefix.$this->name;
    }
}
