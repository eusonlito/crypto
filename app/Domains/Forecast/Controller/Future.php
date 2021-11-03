<?php declare(strict_types=1);

namespace App\Domains\Forecast\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Future extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('forecast-future.meta-title'));

        return $this->page('forecast.future', [
            'list' => $this->actionPost('list'),
            'filters' => $this->request->input(),
        ]);
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'favorite' => $this->auth->preference('forecast-future-favorite', $this->request->input('favorite'), '1'),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        $list = collect();

        foreach (WalletModel::enabled()->whereVisible()->withCurrency()->get() as $each) {
            if ($row = $this->action()->selected($each->currency)) {
                $list->push($row->setRelation('wallet', $each));
            }
        }

        return $list;
    }
}
