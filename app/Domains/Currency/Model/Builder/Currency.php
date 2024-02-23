<?php declare(strict_types=1);

namespace App\Domains\Currency\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class Currency extends BuilderAbstract
{
    /**
     * @param array $codes
     *
     * @return self
     */
    public function byCodes(array $codes): self
    {
        return $this->whereIn('code', $codes);
    }

    /**
     * @param int $platform_id
     *
     * @return self
     */
    public function byPlatformId(int $platform_id): self
    {
        return $this->where('platform_id', $platform_id);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderBy('code', 'ASC');
    }
}
