<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\CreateUpdate as CreateUpdateTrait;
use App\Domains\Wallet\Action\Traits\DataBuyStop as DataBuyStopTrait;
use App\Domains\Wallet\Action\Traits\DataSellStop as DataSellStopTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class Create extends ActionAbstract
{
    use CreateUpdateTrait, DataBuyStopTrait, DataSellStopTrait;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->product();
        $this->data();
        $this->check();
        $this->store();
        $this->message();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataDefault();
        $this->dataBuyStop();
        $this->dataSellStop();
        $this->dataSellStopLoss();
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkBuyStop();
        $this->checkSellStop();
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row = Model::query()->create([
            'address' => $this->data['address'],
            'name' => $this->data['name'],
            'order' => $this->data['order'],

            'amount' => $this->data['amount'],

            'buy_exchange' => $this->data['buy_exchange'],
            'buy_value' => $this->data['buy_value'],

            'current_exchange' => $this->data['current_exchange'],
            'current_value' => $this->data['current_value'],

            'sell_stop' => $this->data['sell_stop'],

            'sell_stop_percent' => $this->data['sell_stop_percent'],
            'sell_stop_amount' => $this->data['sell_stop_amount'],
            'sell_stop_reference' => $this->data['sell_stop_reference'],

            'sell_stop_max_exchange' => $this->data['sell_stop_max_exchange'],
            'sell_stop_max_value' => $this->data['sell_stop_max_value'],
            'sell_stop_max_percent' => $this->data['sell_stop_max_percent'],

            'sell_stop_min_exchange' => $this->data['sell_stop_min_exchange'],
            'sell_stop_min_value' => $this->data['sell_stop_min_value'],
            'sell_stop_min_percent' => $this->data['sell_stop_min_percent'],

            'sell_stop_ai' => $this->data['sell_stop_ai'],

            'buy_stop' => $this->data['buy_stop'],

            'buy_stop_amount' => $this->data['buy_stop_amount'],
            'buy_stop_reference' => $this->data['buy_stop_reference'],

            'buy_stop_max_exchange' => $this->data['buy_stop_max_exchange'],
            'buy_stop_max_value' => $this->data['buy_stop_max_value'],
            'buy_stop_max_percent' => $this->data['buy_stop_max_percent'],
            'buy_stop_max_follow' => $this->data['buy_stop_max_follow'],

            'buy_stop_min_exchange' => $this->data['buy_stop_min_exchange'],
            'buy_stop_min_value' => $this->data['buy_stop_min_value'],
            'buy_stop_min_percent' => $this->data['buy_stop_min_percent'],

            'buy_stop_ai' => $this->data['buy_stop_ai'],

            'sell_stoploss' => $this->data['sell_stoploss'],

            'sell_stoploss_exchange' => $this->data['sell_stoploss_exchange'],
            'sell_stoploss_value' => $this->data['sell_stoploss_value'],
            'sell_stoploss_percent' => $this->data['sell_stoploss_percent'],

            'crypto' => $this->product->crypto,
            'trade' => false,
            'custom' => true,
            'visible' => $this->data['visible'],
            'enabled' => $this->data['enabled'],

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

            'platform_id' => $this->data['platform_id'],
            'currency_id' => $this->product->currency_base_id,
            'product_id' => $this->product->id,
            'user_id' => $this->auth->id,
        ]);
    }

    /**
     * @return void
     */
    protected function message(): void
    {
        service()->message()->success(__('wallet-create.success'));
    }
}
