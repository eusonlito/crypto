<?php declare(strict_types=1);

namespace App\Domains\Product\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Product\Model\Builder\ProductUser as Builder;
use App\Domains\User\Model\User as UserModel;

class ProductUser extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'product_user';

    /**
     * @const string
     */
    public const TABLE = 'product_user';

    /**
     * @const string
     */
    public const FOREIGN = 'product_user_id';

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Product\Model\Builder\ProductUser
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, Product::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, UserModel::FOREIGN);
    }
}
