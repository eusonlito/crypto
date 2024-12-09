<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Controller\Simulator as SimulatorService;

class Simulator extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->load();

        $this->meta('title', __('wallet-simulator.meta-title'));

        return $this->page('wallet.simulator', $this->data());
    }

    /**
     * @return void
     */
    protected function load(): void
    {
        if (empty($id = (int)$this->request->input('id'))) {
            return;
        }

        $this->row($id);
        $this->row->load(['product', 'platform']);
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        $data = ['list' => $this->list()];

        if (isset($this->row)) {
            $data['row'] = $this->row;
            $data += $this->dataFromRow();

            $this->request->merge($data);
        }

        if ($this->request->input('_action') === 'simulator') {
            $data += SimulatorService::new($this->row, $this->request->input())->data();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function dataFromRow(): array
    {
        return [
            'time' => $this->request->input('time'),
            'amount' => $this->request->input('amount', $this->row->amount),
            'buy_exchange' => $this->request->input('buy_exchange', $this->row->buy_exchange),
            'sell_stop_amount' => $this->request->input('sell_stop_amount', $this->row->sell_stop_amount),
            'sell_stop_max_percent' => $this->request->input('sell_stop_max_percent', $this->row->sell_stop_max_percent),
            'sell_stop_min_percent' => $this->request->input('sell_stop_min_percent', $this->row->sell_stop_min_percent),
            'sell_stop' => $this->request->input('sell_stop', $this->row->sell_stop),
            'buy_stop_amount' => $this->request->input('buy_stop_amount', $this->row->buy_stop_amount),
            'buy_stop_min_percent' => $this->request->input('buy_stop_min_percent', $this->row->buy_stop_min_percent),
            'buy_stop_max_percent' => $this->request->input('buy_stop_max_percent', $this->row->buy_stop_max_percent),
            'buy_stop' => $this->request->input('buy_stop', $this->row->buy_stop),
            'buy_stop_max_follow' => $this->request->input('buy_stop_max_follow', $this->row->buy_stop_max_follow),
            'sell_stoploss_percent' => $this->request->input('sell_stoploss_percent', $this->row->sell_stoploss_percent),
            'sell_stoploss' => $this->request->input('sell_stoploss', $this->row->sell_stoploss),
            'exchange_reverse' => $this->request->input('exchange_reverse'),
            'exchange_first' => $this->request->input('exchange_first'),
        ] + $this->row->toArray();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return Model::query()
            ->byUserId($this->auth->id)
            ->whereCrypto()
            ->list()
            ->get()
            ->sortBy('product.name');
    }
}
