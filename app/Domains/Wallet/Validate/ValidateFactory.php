<?php declare(strict_types=1);

namespace App\Domains\Wallet\Validate;

use App\Domains\Shared\Validate\ValidateFactoryAbstract;

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
    public function update(): array
    {
        return $this->handle(Update::class);
    }

    /**
     * @return array
     */
    public function updateBoolean(): array
    {
        return $this->handle(UpdateBoolean::class);
    }

    /**
     * @return array
     */
    public function updateBuyStop(): array
    {
        return $this->handle(UpdateBuyStop::class);
    }

    /**
     * @return array
     */
    public function updateColumn(): array
    {
        return $this->handle(UpdateColumn::class);
    }

    /**
     * @return array
     */
    public function updateSellStop(): array
    {
        return $this->handle(UpdateSellStop::class);
    }
}
