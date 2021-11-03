<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Action\Traits\CreateUpdate as CreateUpdateTrait;
use App\Domains\Wallet\Model\Wallet as Model;

class Update extends ActionAbstract
{
    use CreateUpdateTrait;

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
    protected function store(): void
    {
        if ($this->row->crypto) {
            $this->storeCrypto();
        }

        $this->row->order = $this->data['order'];

        $this->row->processing = false;
        $this->row->visible = $this->data['visible'];
        $this->row->enabled = $this->data['enabled'];
        $this->row->updated_at = date('Y-m-d H:i:s');
        $this->row->currency_id = $this->product->currency_base_id;
        $this->row->product_id = $this->product->id;

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function storeCrypto(): void
    {
        if ($this->row->custom) {
            $this->storeCryptoCustom();
        }

        $this->row->buy_exchange = $this->data['buy_exchange'];
        $this->row->buy_value = $this->data['buy_value'];

        $this->row->sell_stop_amount = $this->data['sell_stop_amount'];

        $this->row->sell_stop_max = $this->data['sell_stop_max'];
        $this->row->sell_stop_max_value = $this->data['sell_stop_max_value'];
        $this->row->sell_stop_max_percent = $this->data['sell_stop_max_percent'];
        $this->row->sell_stop_max_at = $this->data['sell_stop_max_at'];

        $this->row->sell_stop_min = $this->data['sell_stop_min'];
        $this->row->sell_stop_min_value = $this->data['sell_stop_min_value'];
        $this->row->sell_stop_min_percent = $this->data['sell_stop_min_percent'];
        $this->row->sell_stop_min_at = $this->data['sell_stop_min_at'];

        $this->row->sell_stop_percent = $this->data['sell_stop_percent'];

        $this->row->sell_stop = $this->data['sell_stop'];

        $this->row->buy_stop_amount = $this->data['buy_stop_amount'];

        $this->row->buy_stop_max = $this->data['buy_stop_max'];
        $this->row->buy_stop_max_value = $this->data['buy_stop_max_value'];
        $this->row->buy_stop_max_percent = $this->data['buy_stop_max_percent'];
        $this->row->buy_stop_max_at = $this->data['buy_stop_max_at'];

        $this->row->buy_stop_min = $this->data['buy_stop_min'];
        $this->row->buy_stop_min_value = $this->data['buy_stop_min_value'];
        $this->row->buy_stop_min_percent = $this->data['buy_stop_min_percent'];
        $this->row->buy_stop_min_at = $this->data['buy_stop_min_at'];

        $this->row->buy_stop_percent = $this->data['buy_stop_percent'];

        $this->row->buy_stop = $this->data['buy_stop'];

        $this->row->sell_stoploss_exchange = $this->data['sell_stoploss_exchange'];
        $this->row->sell_stoploss_value = $this->data['sell_stoploss_value'];
        $this->row->sell_stoploss_percent = $this->data['sell_stoploss_percent'];
        $this->row->sell_stoploss_at = $this->data['sell_stoploss_at'];

        $this->row->sell_stoploss = $this->data['sell_stoploss'];
    }

    /**
     * @return void
     */
    protected function storeCryptoCustom(): void
    {
        $this->row->address = $this->data['address'];
        $this->row->name = $this->data['name'];

        $this->row->amount = $this->data['amount'];
    }

    /**
     * @return void
     */
    protected function message(): void
    {
        service()->message()->success(__('wallet-update.success'));
    }
}
