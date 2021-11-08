<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('order-index.meta-title'));

        return $this->page('order.index', [
            'filters' => $this->request->input(),
            'list' => $this->list(),
            'filled_options' => $this->filledOptions(),
            'side_options' => $this->sideOptions(),
            'platforms' => PlatformModel::list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function list(): LengthAwarePaginator
    {
        $q = Model::byUserId($this->auth->id)->withRelations()->list();

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if (strlen($filter = $this->request->input('filled'))) {
            $q->whereFilled((bool)$filter);
        }

        if ($filter = $this->request->input('side')) {
            $q->bySide($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_start', ''))) {
            $q->byCreatedAtStart($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_end', ''))) {
            $q->byCreatedAtEnd($filter);
        }

        return $q->paginate(50);
    }

    /**
     * @return array
     */
    protected function filledOptions(): array
    {
        return [
            '1' => __('order-index.filled-yes'),
            '0' => __('order-index.filled-no'),
        ];
    }

    /**
     * @return array
     */
    protected function sideOptions(): array
    {
        return [
            'buy' => __('order-index.side-buy'),
            'sell' => __('order-index.side-sell'),
        ];
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'platform_id' => (int)$this->auth->preference('order-index-platform_id', $this->request->input('platform_id'), 0),
            'filled' => $this->auth->preference('order-index-filled', $this->request->input('filled'), ''),
            'side' => $this->auth->preference('order-index-side', $this->request->input('side'), ''),
            'date_start' => $this->auth->preference('order-index-date_start', $this->request->input('date_start'), ''),
            'date_end' => $this->auth->preference('order-index-date_end', $this->request->input('date_end'), ''),
        ]);
    }
}
