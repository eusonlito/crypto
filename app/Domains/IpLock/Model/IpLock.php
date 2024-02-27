<?php declare(strict_types=1);

namespace App\Domains\IpLock\Model;

use App\Domains\Core\Model\ModelAbstract;
use App\Domains\IpLock\Model\Builder\IpLock as Builder;

class IpLock extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'ip_lock';

    /**
     * @const string
     */
    public const TABLE = 'ip_lock';

    /**
     * @const string
     */
    public const FOREIGN = 'ip_lock_id';

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\IpLock\Model\Builder\IpLock
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }
}
