<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Order\Service\Controller\Status as StatusService;

class Status extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function __invoke(): Response | JsonResponse
    {
        $this->filters();

        if ($this->request->wantsJson()) {
            return $this->responseJson();
        }

        $this->meta('title', __('order-status.meta-title'));

        return $this->page('order.status', [
            'filters' => $this->request->input(),
            'list' => (new StatusService($this->auth, $this->request))->get(),
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
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseJson(): JsonResponse
    {
        if ($wallet_id = (int)$this->request->input('wallet_id')) {
            $row = (new StatusService($this->auth, $this->request))->get()->firstWhere('wallet.id', $wallet_id);
        } else {
            $row = null;
        }

        return $this->json($row);
    }
}
