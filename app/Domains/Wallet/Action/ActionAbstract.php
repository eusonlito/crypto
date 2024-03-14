<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Core\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Exceptions\UnexpectedValueException;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Wallet\Model\Wallet
     */
    protected ?Model $row;

    /**
     * @param float $amount
     * @param string $mode
     *
     * @return float
     */
    protected function roundFixed(float $amount, string $mode): float
    {
        $decimals = match ($mode) {
            'quantity' => $this->product->quantity_decimal,
            'price' => $this->product->price_decimal,
            default => throw new UnexpectedValueException(sprintf('Invalid decimals mode %s', $mode)),
        };

        return helper()->roundFixed($amount, $decimals);
    }

    /**
     * @return float
     */
    protected function buyStopOrderCreateAmount(): float
    {
        $amount = $this->row->buy_stop_amount;
        $cash = $this->buyStopOrderCreateAmountAvailable();

        if ($cash === null) {
            return $this->roundFixed($amount, 'quantity');
        }

        $value = $amount * $this->row->buy_stop_max_exchange;
        $max = $cash * 0.995;

        if ($value > $max) {
            $amount = $max * $amount / $value;
        }

        return $this->roundFixed($amount, 'quantity');
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

        return $this->roundFixed($amount + ($amount * 0.0001), 'price');
    }

    /**
     * @return float
     */
    protected function sellStopOrderCreateAmount(): float
    {
        return $this->roundFixed(min($this->row->amount, $this->row->sell_stop_amount), 'quantity');
    }

    /**
     * @return float
     */
    protected function sellStopOrderCreateLimit(): float
    {
        $amount = $this->row->sell_stop_min_exchange;

        return $this->roundFixed($amount - ($amount * 0.0001), 'price');
    }
}
