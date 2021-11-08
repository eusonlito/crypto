<?php declare(strict_types=1);

namespace App\Domains\Order\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Sync extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        $this->filters();

        if ($response = $this->actionPost('syncByProducts')) {
            return $response;
        }

        $this->meta('title', __('order-sync.meta-title'));

        return $this->page('order.sync', [
            'filters' => $this->request->input(),
            'products' => $this->products(),
            'platforms' => PlatformModel::list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function syncByProducts(): RedirectResponse
    {
        $products = ProductModel::byIds((array)$this->request->input('product_ids'))
            ->withPlatformAndPivot($this->auth->id)
            ->get();

        $this->action()->syncByProducts($products);

        return redirect()->route('order.index');
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'platform_id' => (int)$this->auth->preference('order-sync-platform_id', $this->request->input('platform_id'), 0),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function products(): Collection
    {
        $q = ProductModel::whereCrypto()->withPlatform()->withUserPivotFavoriteByUserId($this->auth->id);

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if (empty($ids = (array)$this->request->input('product_ids'))) {
            $ids = $this->productIds();
        }

        return $q->get()->each(static function ($value) use ($ids) {
            $value->selected = in_array($value->id, $ids);
        })->sort(static function ($a, $b) {
            if ($a->selected && ($b->selected === false)) {
                return -1;
            }

            if (($a->selected === false) && $b->selected) {
                return 1;
            }

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
     * @return array
     */
    protected function productIds(): array
    {
        return array_unique(array_merge(
            Model::byUserId($this->auth->id)->whereProductCrypto()->groupByProductId()->pluck('product_id')->toArray(),
            WalletModel::byUserId($this->auth->id)->whereCrypto()->groupByProductId()->pluck('product_id')->toArray(),
        ));
    }
}
