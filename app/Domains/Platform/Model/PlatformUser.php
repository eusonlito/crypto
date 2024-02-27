<?php declare(strict_types=1);

namespace App\Domains\Platform\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Platform\Model\Builder\PlatformUser as Builder;
use App\Domains\User\Model\User as UserModel;

class PlatformUser extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'platform_user';

    /**
     * @const string
     */
    public const TABLE = 'platform_user';

    /**
     * @const string
     */
    public const FOREIGN = 'platform_user_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = ['settings'];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\Platform\Model\Builder\PlatformUser
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
        return $this->belongsTo(Platform::class, Platform::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, UserModel::FOREIGN);
    }
}
