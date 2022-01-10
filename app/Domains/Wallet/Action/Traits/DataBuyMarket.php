<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action\Traits;

trait DataBuyMarket
{
    /**
     * @return void
     */
    protected function dataBuyMarket(): void
    {
        $this->data['buy_market_percent'] = abs((float)$this->data['buy_market_percent']);

        if ($this->data['buy_market_percent'] === 0.0) {
            $this->dataBuyMarketZero();
            return;
        }

        $this->data['buy_market_amount'] = abs((float)$this->data['buy_market_amount']);
        $this->data['buy_market_reference'] = abs((float)$this->data['buy_market_reference']);

        $this->data['buy_market_exchange'] = $this->data['buy_market_reference'] * (1 + ($this->data['buy_market_percent'] / 100));
        $this->data['buy_market_value'] = $this->data['buy_market_amount'] * $this->data['buy_market_exchange'];
        $this->data['buy_market_at'] = $this->data['buy_market_at'] ? $this->row->buy_market_at : null;
    }

    /**
     * @return void
     */
    protected function dataBuyMarketZero(): void
    {
        $this->data['buy_market'] = 0;
        $this->data['buy_market_amount'] = 0;
        $this->data['buy_market_reference'] = 0;
        $this->data['buy_market_percent'] = 0;
        $this->data['buy_market_exchange'] = 0;
        $this->data['buy_market_value'] = 0;
        $this->data['buy_market_at'] = null;
    }
}
