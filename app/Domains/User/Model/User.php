<?php declare(strict_types=1);

namespace App\Domains\User\Model;

use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Language\Model\Language as LanguageModel;
use App\Domains\Platform\Model\PlatformUser as PlatformUserModel;
use App\Domains\User\Model\Builder\User as Builder;

class User extends ModelAbstract implements Authenticatable
{
    use AuthenticatableTrait;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @const string
     */
    public const TABLE = 'user';

    /**
     * @const string
     */
    public const FOREIGN = 'user_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'admin' => 'boolean',
        'enabled' => 'boolean',
        'preferences' => 'array',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'code', 'password', 'preferences', 'remember_token', 'tfa_secret',
    ];

    /**
     * @param \Illuminate\Database\Query\Builder $q
     *
     * @return \App\Domains\User\Model\Builder\User
     */
    public function newEloquentBuilder($q): Builder
    {
        return new Builder($q);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, LanguageModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plarformsPivot(): HasMany
    {
        return $this->hasMany(PlatformUserModel::class, static::FOREIGN);
    }

    /**
     * @param string $key
     * @param mixed $input
     * @param mixed $default = null
     *
     * @return mixed
     */
    public function preference(string $key, $input, $default = null)
    {
        if ($input !== null) {
            return $this->preferenceSet($key, $input);
        }

        if (($value = $this->preferences[$key] ?? null) !== null) {
            return $value;
        }

        if (isset($default)) {
            return $this->preferenceSet($key, $default);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function preferenceSet(string $key, $value)
    {
        $preferences = (array)$this->preferences;

        if (($preferences[$key] ?? null) !== $value) {
            $this->preferences = [$key => $value] + $preferences;
            $this->save();
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function activated(): bool
    {
        return (bool)$this->activated_at;
    }
}
