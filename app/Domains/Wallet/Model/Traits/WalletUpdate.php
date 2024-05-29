<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model\Traits;

trait WalletUpdate
{
    /**
     * @param float $exchange
     *
     * @return void
     */
    public function updateBuy(float $exchange): void
    {
        $this->buy_exchange = $exchange;
        $this->buy_value = $this->buy_exchange * $this->amount;
    }

    /**
     * @return void
     */
    public function updateBuyStopDisable(): void
    {
        $this->updateBuyStopEnable();

        $this->buy_stop = false;
    }

    /**
     * @return void
     */
    public function updateBuyStopEnable(): void
    {
        $this->buy_stop = $this->buy_stop_max_exchange
            && $this->buy_stop_min_percent
            && $this->buy_stop_max_percent;

        $this->buy_stop_reference = $this->buy_exchange;

        $this->buy_stop_min_exchange = $this->buy_stop_reference * (1 - ($this->buy_stop_min_percent / 100));
        $this->buy_stop_max_exchange = $this->buy_stop_min_exchange * (1 + ($this->buy_stop_max_percent / 100));

        $this->buy_stop_amount = $this->buy_stop_max_value / $this->buy_stop_max_exchange;
        $this->buy_stop_min_value = $this->buy_stop_amount * $this->buy_stop_min_exchange;

        $this->buy_stop_min_at = null;
        $this->buy_stop_min_executable = false;

        $this->buy_stop_max_at = null;
        $this->buy_stop_max_executable = false;

        $this->order_buy_stop_id = null;
    }

    /**
     * @return void
     */
    public function updateSellStopDisable(): void
    {
        $this->updateSellStopEnable();

        $this->sell_stop = false;
    }

    /**
     * @return void
     */
    public function updateSellStopEnable(): void
    {
        $this->sell_stop = $this->sell_stop_percent
            && $this->sell_stop_max_percent
            && $this->sell_stop_min_percent;

        $this->sell_stop_reference = $this->buy_exchange;
        $this->sell_stop_amount = $this->amount * $this->sell_stop_percent / 100;

        $this->sell_stop_max_exchange = $this->sell_stop_reference * (1 + ($this->sell_stop_max_percent / 100));
        $this->sell_stop_max_value = $this->sell_stop_amount * $this->sell_stop_max_exchange;
        $this->sell_stop_max_at = null;
        $this->sell_stop_max_executable = false;

        $this->sell_stop_min_exchange = $this->sell_stop_max_exchange * (1 - ($this->sell_stop_min_percent / 100));
        $this->sell_stop_min_value = $this->sell_stop_amount * $this->sell_stop_min_exchange;
        $this->sell_stop_min_at = null;
        $this->sell_stop_min_executable = false;

        $this->order_sell_stop_id = null;
    }

    /**
     * @return void
     */
    public function updateSellStopLossDisable(): void
    {
        $this->updateSellStopLossEnable();

        $this->sell_stoploss = false;
    }

    /**
     * @return void
     */
    public function updateSellStopLossEnable(): void
    {
        $this->sell_stoploss = boolval($this->sell_stoploss_percent);
        $this->sell_stoploss_exchange = $this->buy_exchange * (1 - ($this->sell_stoploss_percent / 100));
        $this->sell_stoploss_value = $this->amount * $this->sell_stoploss_exchange;
        $this->sell_stoploss_at = null;
        $this->sell_stoploss_executable = false;
    }
}
