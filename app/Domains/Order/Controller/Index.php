<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\Response;
use App\Domains\Order\Service\Controller\Index as IndexService;
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
            'list' => (new IndexService($this->auth, $this->request))->get(),
            'filled_options' => $this->filledOptions(),
            'side_options' => $this->sideOptions(),
            'custom_options' => $this->customOptions(),
            'platforms' => PlatformModel::query()->byUserId($this->auth->id)->list()->get(),
            'filled' => (bool)$this->request->input('filled'),
        ]);
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
     * @return array
     */
    protected function customOptions(): array
    {
        return [
            '1' => __('order-index.custom-yes'),
            '0' => __('order-index.custom-no'),
        ];
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'search' => $this->request->input('search', ''),
            'date_start' => $this->request->input('date_start', ''),
            'date_end' => $this->request->input('date_end', ''),
            'platform_id' => (int)$this->auth->preference('order-index-platform_id', $this->request->input('platform_id'), 0),
            'filled' => $this->auth->preference('order-index-filled', $this->request->input('filled'), ''),
            'side' => $this->auth->preference('order-index-side', $this->request->input('side'), ''),
            'custom' => $this->auth->preference('order-index-custom', $this->request->input('custom'), ''),
        ]);
    }
}
