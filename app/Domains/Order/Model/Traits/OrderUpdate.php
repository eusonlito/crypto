<?php declare(strict_types=1);

namespace App\Domains\Order\Model\Traits;

trait OrderUpdate
{
    /**
     * @return void
     */
    public function updatePrevious(): void
    {
        $this->previous_price = static::query()
            ->byWalletId($this->wallet_id)
            ->bySide($this->side === 'buy' ? 'sell' : 'buy')
            ->byCreatedAtBefore($this->created_at)
            ->whereFilled()
            ->value('price') ?: 0;

        $this->previous_value = $this->previous_price * $this->amount;
        $this->previous_percent = helper()->percent($this->value, $this->previous_value);
    }
}
