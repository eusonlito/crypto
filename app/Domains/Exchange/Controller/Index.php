<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use Illuminate\Http\Response;
use App\Domains\Exchange\Service\Report\Report as ReportService;
use App\Domains\Platform\Model\Platform as PlatformModel;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('exchange-index.meta-title'));

        return $this->page('exchange.index', [
            'list' => (new ReportService($this->request->input()))->get(),
            'platforms' => PlatformModel::list()->get(),
            'filters' => $this->request->input(),
        ]);
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'top' => $this->auth->preference('exchange-index-top', $this->request->input('top'), '50'),
            'time' => (int)$this->auth->preference('exchange-index-time', $this->request->input('time'), 60),
            'platform_id' => (int)$this->auth->preference('exchange-index-platform_id', $this->request->input('platform_id'), 0),
        ]);
    }
}
