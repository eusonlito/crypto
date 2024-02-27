<?php declare(strict_types=1);

namespace App\Domains\Platform\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Platform\Model\Builder\Platform as Builder;
use App\Domains\User\Model\User as UserModel;

class Platform extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'platform';

    /**
     * @const string
     */
    public const TABLE = 'platform';

    /**
     * @const string
     */
    public const FOREIGN = 'platform_id';

    /**
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('enabled', function (Builder $builder) {
            $builder->enabled();
        });
    }

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Platform\Model\Builder\Platform
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userPivot(): HasOne
    {
        return $this->hasOne(PlatformUser::class, static::FOREIGN);
    }

    /**
     * @param \App\Domains\User\Model\User $user
     *
     * @return bool
     */
    public function userPivotLoad(UserModel $user): bool
    {
        if ($this->relationLoaded('userPivot') && $this->userPivot) {
            return true;
        }

        if ($userPivot = $this->userPivot()->byUserId($user->id)->first()) {
            $this->setRelation('userPivot', $userPivot);
        }

        return (bool)$userPivot;
    }
}
