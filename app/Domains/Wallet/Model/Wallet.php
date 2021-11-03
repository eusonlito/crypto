<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\User\Model\User as UserModel;
use App\Domains\Wallet\Model\Builder\Wallet as Builder;
use App\Domains\Wallet\Model\Traits\WalletSql as WalletSqlTrait;
use App\Domains\Shared\Model\ModelAbstract;

class Wallet extends ModelAbstract
{
    use WalletSqlTrait;

    /**
     * @var string
     */
    protected $table = 'wallet';

    /**
     * @const string
     */
    public const TABLE = 'wallet';

    /**
     * @const string
     */
    public const FOREIGN = 'wallet_id';

    /**
     * @var array
     */
    protected $casts = [
        'buy_stop' => 'boolean',
        'sell_stop' => 'boolean',
        'sell_stoploss' => 'boolean',
        'processing' => 'boolean',
        'custom' => 'boolean',
        'crypto' => 'boolean',
        'trade' => 'boolean',
        'enabled' => 'boolean',
        'visible' => 'boolean',
    ];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Wallet\Model\Builder\Wallet
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
