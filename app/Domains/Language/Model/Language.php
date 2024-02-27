<?php declare(strict_types=1);

namespace App\Domains\Language\Model;

use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Language\Model\Builder\Language as Builder;

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
     * @return \App\Domains\Language\Model\Builder\Language
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }
}
