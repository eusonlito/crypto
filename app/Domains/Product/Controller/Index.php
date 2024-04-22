<?php declare(strict_types=1);

namespace App\Domains\Product\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as Model;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->filters();

        $this->meta('title', __('product-index.meta-title'));

        return $this->page('product.index', [
            'filters' => $this->request->input(),
            'list' => $this->list(),
            'platforms' => PlatformModel::query()->byUserId($this->auth->id)->list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        $q = Model::query()->withPlatform()->withUserPivotFavoriteByUserId($this->auth->id);

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if ($this->request->input('favorite')) {
            $q->whereUserPivotFavoriteByUserId($this->auth->id);
        }

        if (strlen($filter = $this->request->input('enabled'))) {
            $q->enabled((bool)$filter);
        }

        return $q->get()->sort(static function ($a, $b) {
            if (($a->userPivot === null) && ($b->userPivot === null)) {
                return $a->code <=> $b->code;
            }

            if ($a->userPivot && $b->userPivot) {
                return $a->code <=> $b->code;
            }

            return $a->userPivot ? -1 : 1;
        })->values();
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'platform_id' => (int)$this->auth->preference('product-index-platform_id', $this->request->input('platform_id'), 0),
            'favorite' => $this->auth->preference('product-index-favorite', $this->request->input('favorite'), ''),
            'enabled' => $this->auth->preference('product-index-enabled', $this->request->input('enabled'), ''),
        ]);
    }
}
