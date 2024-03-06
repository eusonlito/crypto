<?php declare(strict_types=1);

namespace App\Domains\Language\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class Language extends BuilderAbstract
{
    /**
     * @param string $iso
     *
     * @return self
     */
    public function byCode(string $iso): self
    {
        return $this->where('iso', $iso);
    }

    /**
     * @return self
     */
    public function selectSession(): self
    {
        return $this->select('id', 'iso', 'name', 'locale');
    }

    /**
     * @param ?int $id
     *
     * @return self
     */
    public function whenIdOrDefault(?int $id): self
    {
        return $this->when($id, fn ($q) => $q->byId($id), fn ($q) => $q->whereDefault());
    }

    /**
     * @return self
     */
    public function whereDefault(): self
    {
        return $this->where($this->addTable('iso'), config('app.locale'));
    }
}
