<?php declare(strict_types=1);

namespace App\Domains\Order\Validate;

use App\Domains\Core\Validate\ValidateFactoryAbstract;

class ValidateFactory extends ValidateFactoryAbstract
{
    /**
     * @return array
     */
    public function create(): array
    {
        return $this->handle(Create::class);
    }

    /**
     * @return array
     */
    public function createSimple(): array
    {
        return $this->handle(CreateSimple::class);
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->handle(Update::class);
    }
}
