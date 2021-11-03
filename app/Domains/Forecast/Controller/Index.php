<?php declare(strict_types=1);

namespace App\Domains\Forecast\Controller;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('forecast-index.meta-title'));

        return $this->page('forecast.index', [
            'filters' => $this->request->input(),
            'list' => $this->list(),
            'wallets' => WalletModel::listSelect()->get(),
            'selected_options' => $this->selectedOptions(),
            'side_options' => $this->sideOptions(),
        ]);
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function list(): LengthAwarePaginator
    {
        $q = Model::byUserId($this->auth->id)->whereValid()->withRelations()->list();

        if (strlen($filter = $this->request->input('selected'))) {
            $q->whereSelected((bool)$filter);
        }

        if ($filter = $this->request->input('side')) {
            $q->bySide($filter);
        }

        if ($wallet_id = (int)$this->request->input('wallet_id')) {
            $q->byWalletId($wallet_id);
        }

        return $q->paginate(50);
    }

    /**
     * @return array
     */
    protected function selectedOptions(): array
    {
        return [
            '1' => __('forecast-index.selected-yes'),
            '0' => __('forecast-index.selected-no'),
        ];
    }

    /**
     * @return array
     */
    protected function sideOptions(): array
    {
        return [
            'buy' => __('forecast-index.side-buy'),
            'sell' => __('forecast-index.side-sell'),
        ];
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'selected' => $this->auth->preference('forecast-index-selected', $this->request->input('selected'), ''),
            'side' => $this->auth->preference('forecast-index-side', $this->request->input('side'), ''),
            'wallet_id' => (int)$this->auth->preference('forecast-index-wallet_id', $this->request->input('wallet_id')),
        ]);
    }
}
