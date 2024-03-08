<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class WalletHistory extends BuilderAbstract
{
    /**
     * @param int $wallet_id
     *
     * @return self
     */
    public function byWalletId(int $wallet_id): self
    {
        return $this->where('wallet_id', $wallet_id);
    }
}
