<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\Response;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Order\Service\Controller\Chart as ChartService;

class Chart extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('order-chart.meta-title'));

        return $this->page('order.chart', $this->data());
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'wallet_id' => (int)$this->request->input('wallet_id'),
            'platform_id' => (int)$this->auth->preference('order-chart-platform_id', $this->request->input('platform_id'), 0),
            'date_start' => $this->auth->preference('order-chart-date_start', $this->request->input('date_start'), ''),
            'date_end' => $this->auth->preference('order-chart-date_end', $this->request->input('date_end'), ''),
        ]);
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return $this->service() + [
            'filters' => $this->request->input(),
            'platforms' => PlatformModel::query()->byUserId($this->auth->id)->list()->get(),
        ];
    }

    /**
     * @return array
     */
    protected function service(): array
    {
        return ChartService::new($this->auth, $this->request)->get();
    }
}
