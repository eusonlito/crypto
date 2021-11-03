<?php declare(strict_types=1);

namespace App\Domains\Language\Model;

use App\Domains\Language\Model\Builder\Language as Builder;
use App\Domains\Shared\Model\ModelAbstract;

class Language extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'language';

    /**
     * @const string
     */
    public const TABLE = 'language';

    /**
     * @const string
     */
    public const FOREIGN = 'language_id';

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($q)
    {
        return new Builder($q);
    }
}
