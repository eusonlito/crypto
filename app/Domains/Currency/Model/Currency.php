<?php declare(strict_types=1);

namespace App\Domains\Currency\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Currency\Model\Builder\Currency as Builder;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Core\Model\ModelAbstract;

class Currency extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'currency';

    /**
     * @const string
     */
    public const TABLE = 'currency';

    /**
     * @const string
     */
    public const FOREIGN = 'currency_id';

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
}
