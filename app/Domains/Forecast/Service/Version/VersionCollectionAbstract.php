<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version;

use Illuminate\Support\Collection;

abstract class VersionCollectionAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $list;

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return self
     */
    public function __construct(Collection $list)
    {
        $this->list = $list;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    abstract public function sort(): Collection;
}
