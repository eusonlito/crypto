<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\DataBuyMarket as DataBuyMarketTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class UpdateBuyMarket extends ActionAbstract
{
    use DataBuyMarketTrait;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataBuyMarket();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->buy_market = $this->data['buy_market'];

        $this->row->buy_market_amount = $this->data['buy_market_amount'];
        $this->row->buy_market_reference = $this->data['buy_market_reference'];

        $this->row->buy_market_exchange = $this->data['buy_market_exchange'];
        $this->row->buy_market_value = $this->data['buy_market_value'];
        $this->row->buy_market_percent = $this->data['buy_market_percent'];
        $this->row->buy_market_at = $this->data['buy_market_at'];

        $this->row->save();
    }
}
