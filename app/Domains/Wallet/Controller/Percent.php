<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;
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
        $this->row->load(['product', 'platform']);
        $this->requestMergeWithRow();
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        $data = ['list' => $this->list()];

        if (empty($this->row)) {
            return $data;
        }

        $service = new PercentCalculator($this->row, $this->request->isMethod('post') ? $this->request->input() : []);

        return $data + [
            'exchanges' => $service->getExchanges(),
            'orders' => $service->getOrders(),
            'row' => $service->getRow(),
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
}