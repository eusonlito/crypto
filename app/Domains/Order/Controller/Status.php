<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Order\Service\Status\Status as StatusService;

class Status extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function __invoke(): Response | JsonResponse
    {
        $this->meta('title', __('order-status.meta-title'));

        $this->filters();

        $list = (new StatusService())->listByRequest($this->auth, $this->request);

        if ($this->request->wantsJson()) {
            return $this->responseJson($list);
        }

        return $this->page('order.status', [
            'filters' => $this->request->input(),
            'list' => $list,
            'platforms' => PlatformModel::list()->get(),
        ]);
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'wallet_id' => (int)$this->request->input('wallet_id'),
            'product_id' => (int)$this->request->input('product_id'),
            'platform_id' => (int)$this->auth->preference('order-status-platform_id', $this->request->input('platform_id'), 0),
            'date_start' => $this->auth->preference('order-status-date_start', $this->request->input('date_start'), ''),
            'date_end' => $this->auth->preference('order-status-date_end', $this->request->input('date_end'), ''),
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseJson(Collection $list): JsonResponse
    {
        if ($wallet_id = (int)$this->request->input('wallet_id')) {
            return $this->json($list->firstWhere('wallet.id', $wallet_id));
        }

        return $this->json();
    }
}
