<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Percent\Calculator as PercentCalculator;

class Percent extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->load();

        $this->meta('title', __('wallet-percent.meta-title'));

        return $this->page('wallet.percent', $this->data());
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
        $this->requestMergeWithRow();
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        $data = [
            'list' => $this->list(),
            'row' => ($this->row ?? null),
        ];

        if (empty($data['row'])) {
            return $data;
        }

        $exchanges = $this->exchanges();

        return $data + [
            'exchange' => $exchanges->last(),
            'exchanges' => $exchanges,
            'result' => PercentCalculator::new($this->row, $exchanges)->get(),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return Model::byUserId($this->auth->id)
            ->whereCrypto()
            ->list()
            ->get()
            ->sortBy('product.name');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function exchanges(): Collection
    {
        return ExchangeModel::byProductId($this->row->product->id)
            ->afterDate(date('Y-m-d H:i:s', strtotime('-15 days')))
            ->pluck('exchange', 'created_at');
    }
}
