<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Wallet\Model\Wallet as Model;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('wallet-index.meta-title'));

        return $this->page('wallet.index', [
            'list' => $this->list(),
            'filters' => $this->request->input(),
            'platforms' => PlatformModel::query()->list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        $q = Model::query()->byUserId($this->auth->id)->list();

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if (strlen($filter = $this->request->input('enabled'))) {
            $q->enabled((bool)$filter);
        }

        if (strlen($filter = $this->request->input('visible'))) {
            $q->whereVisible((bool)$filter);
        }

        return $q->get();
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'platform_id' => (int)$this->auth->preference('wallet-index-platform_id', $this->request->input('platform_id'), 0),
            'enabled' => $this->auth->preference('wallet-index-enabled', $this->request->input('enabled'), ''),
            'visible' => $this->auth->preference('wallet-index-visible', $this->request->input('visible'), ''),
        ]);
    }
}
