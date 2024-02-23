<?php declare(strict_types=1);

namespace App\Domains\Forecast\Validate;

use App\Domains\Core\Validate\ValidateFactoryAbstract;

class ValidateFactory extends ValidateFactoryAbstract
{
    /**
     * @return array
     */
    public function all(): array
    {
        return $this->handle(All::class);
    }

    /**
     * @return array
     */
    public function selected(): array
    {
        return $this->handle(Selected::class);
    }
}
