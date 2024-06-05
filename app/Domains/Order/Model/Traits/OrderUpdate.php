<?php declare(strict_types=1);

namespace App\Domains\Order\Model\Traits;

trait OrderUpdate
{
    /**
     * @return void
     */
    public function updatePrevious(): void
    {
        if ($this->wallet_id && $this->created_at) {
            static::previousSetByWalletIdAndCreatedAt($this->wallet_id, $this->created_at);
        } elseif ($this->wallet_id) {
            static::previousSetByWalletId($this->wallet_id);
        } else {
            static::previousSet();
        }
    }
}
