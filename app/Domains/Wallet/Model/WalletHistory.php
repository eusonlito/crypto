<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\User\Model\User as UserModel;
use App\Domains\Wallet\Model\Builder\WalletHistory as Builder;

class WalletHistory extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'wallet_history';

    /**
     * @const string
     */
    public const TABLE = 'wallet_history';

    /**
     * @const string
     */
    public const FOREIGN = 'wallet_history_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'object',
    ];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Wallet\Model\Builder\Wallet
     */
    public function newEloquentBuilder($q): Builder
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, Wallet::FOREIGN);
    }
}
