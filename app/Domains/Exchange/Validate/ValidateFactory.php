<?php declare(strict_types=1);

namespace App\Domains\Exchange\Validate;

use App\Domains\Core\Validate\ValidateFactoryAbstract;

class ValidateFactory extends ValidateFactoryAbstract
{
    /**
     * @return array
     */
    public function clearOld(): array
    {
        return $this->handle(ClearOld::class);
    }
}
