<?php declare(strict_types=1);

namespace App\Domains\Forecast\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Forecast\Model\Builder\Forecast as Builder;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\User\Model\User as UserModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Forecast extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'forecast';

    /**
     * @const string
     */
    public const TABLE = 'forecast';

    /**
     * @const string
     */
    public const FOREIGN = 'forecast_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'keys' => 'array',
        'values' => 'array',
        'valid' => 'boolean',
        'selected' => 'boolean',
    ];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Forecast\Model\Builder\Forecast
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
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
        return $this->belongsTo(WalletModel::class, WalletModel::FOREIGN);
    }
}
