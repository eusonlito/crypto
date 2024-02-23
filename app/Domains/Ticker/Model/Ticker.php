<?php declare(strict_types=1);

namespace App\Domains\Ticker\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\User\Model\User as UserModel;
use App\Domains\Ticker\Model\Builder\Ticker as Builder;
use App\Domains\Ticker\Model\Traits\TickerSql as TickerSqlTrait;
use App\Domains\Core\Model\ModelAbstract;

class Ticker extends ModelAbstract
{
    use TickerSqlTrait;

    /**
     * @var string
     */
    protected $table = 'ticker';

    /**
     * @const string
     */
    public const TABLE = 'ticker';

    /**
     * @const string
     */
    public const FOREIGN = 'ticker_id';

    /**
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($q)
    {
        return new Builder($q);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyModel::class, CurrencyModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(ExchangeModel::class, ProductModel::FOREIGN, ProductModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(PlatformModel::class, PlatformModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, ProductModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, UserModel::FOREIGN);
    }
}
