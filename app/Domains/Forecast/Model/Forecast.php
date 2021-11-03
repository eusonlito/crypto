<?php declare(strict_types=1);

namespace App\Domains\Forecast\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\User\Model\User as UserModel;
use App\Domains\Forecast\Model\Builder\Forecast as Builder;
use App\Domains\Shared\Model\ModelAbstract;
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
     * @var array
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
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($q)
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
