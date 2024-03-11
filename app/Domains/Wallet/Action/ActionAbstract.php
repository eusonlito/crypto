<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Wallet\Model\Wallet as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Wallet\Model\Wallet
     */
    protected ?Model $row;

    /**
     * @param float $amount
     *
     * @return float
     */
    protected function roundFixed(float $amount): float
    {
        return helper()->roundFixed($amount, $this->product->quantity_decimal);
    }

    /**
     * @return float
     */
    protected function buyStopOrderCreateAmount(): float
    {
        $amount = $this->row->buy_stop_amount;
        $cash = $this->buyStopOrderCreateAmountAvailable();

        if ($cash === null) {
            return $this->roundFixed($amount);
        }

        $value = $amount * $this->row->buy_stop_max_exchange;
        $max = $cash * 0.995;

        if ($value > $max) {
            $amount = $max * $amount / $value;
        }

        return $this->roundFixed($amount);
    }

    /**
     * @return ?float
     */
    protected function buyStopOrderCreateAmountAvailable(): ?float
    {
        return Model::query()
            ->byUserId($this->auth->id)
            ->byProductCurrencyBaseIdAndCurrencyQuoteId($this->product->currency_quote_id, $this->product->currency_quote_id)
            ->byPlatformId($this->platform->id)
            ->value('current_value');
    }

    /**
     * @return float
     */
    protected function buyStopOrderCreateLimit(): float
    {
        $amount = $this->row->buy_stop_max_exchange;

        return $this->roundFixed($amount + ($amount * 0.0005));
    }

    /**
     * @return float
     */
    protected function sellStopOrderCreateAmount(): float
    {
        return $this->roundFixed(min($this->row->amount, $this->row->sell_stop_amount));
    }

    /**
     * @return float
     */
    protected function sellStopOrderCreateLimit(): float
    {
        $amount = $this->row->sell_stop_min_exchange;

        return $this->roundFixed($amount - ($amount * 0.0005));
    }
}
