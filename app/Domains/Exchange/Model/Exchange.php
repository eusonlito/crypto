<?php declare(strict_types=1);

namespace App\Domains\Exchange\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Exchange\Model\Builder\Exchange as Builder;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class Exchange extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'exchange';

    /**
     * @const string
     */
    public const TABLE = 'exchange';

    /**
     * @const string
     */
    public const FOREIGN = 'exchange_id';

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Exchange\Model\Builder\Exchange
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
}
