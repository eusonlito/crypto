<?php declare(strict_types=1);

namespace App\Domains\Language\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class Language extends BuilderAbstract
{
    /**
     * @return self
     */
    public function selectSession(): self
    {
        return $this->select('id', 'code', 'name', 'locale');
    }
}
