<?php declare(strict_types=1);

namespace App\Domains\Platform\Model\Builder;

use App\Domains\Platform\Model\PlatformUser as PlatformUserModel;
use App\Domains\Core\Model\Builder\BuilderAbstract;

class Platform extends BuilderAbstract
{
    /**
     * @param string $code
     *
     * @return self
     */
    public function byCode(string $code): self
    {
        return $this->where('code', $code);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderBy('name', 'ASC');
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function byUserId(int $user_id): self
    {
        return $this->whereIn('id', PlatformUserModel::select('platform_id')->where('user_id', $user_id));
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function withUserPivot(int $user_id): self
    {
        return $this->with(['userPivot' => static fn ($q) => $q->byUserId($user_id)]);
    }
}
