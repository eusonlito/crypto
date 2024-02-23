<?php declare(strict_types=1);

namespace App\Domains\Order\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Order\Model\Builder\Order as Builder;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Order extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'order';

    /**
     * @const string
     */
    public const TABLE = 'order';

    /**
     * @const string
     */
    public const FOREIGN = 'order_id';

    /**
     * @var array
     */
    protected $casts = [
        'filled' => 'boolean',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function wallet(): HasOneThrough
    {
        return $this->hasOneThrough(WalletModel::class, ProductModel::class, 'id', 'product_id', 'product_id', 'id');
    }
}
