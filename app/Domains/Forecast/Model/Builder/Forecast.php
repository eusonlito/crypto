<?php declare(strict_types=1);

namespace App\Domains\Forecast\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class Forecast extends BuilderAbstract
{
    /**
     * @return self
     */
    public function withRelations(): self
    {
        return $this->with(['platform', 'product', 'wallet']);
    }

    /**
     * @param string $side
     *
     * @return self
     */
    public function bySide(string $side): self
    {
        return $this->where('side', $side);
    }

    /**
     * @param int $wallet_id
     *
     * @return self
     */
    public function byWalletId(int $wallet_id): self
    {
        return $this->where('wallet_id', $wallet_id);
    }

    /**
     * @param bool $valid = true
     *
     * @return self
     */
    public function whereValid(bool $valid = true): self
    {
        return $this->where('valid', $valid);
    }

    /**
     * @param bool $selected = true
     *
     * @return self
     */
    public function whereSelected(bool $selected = true): self
    {
        return $this->where('selected', $selected);
    }
}
