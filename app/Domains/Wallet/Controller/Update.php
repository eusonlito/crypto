<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class Update extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): Response|RedirectResponse
    {
        $this->row($id);

        if ($response = $this->actions()) {
            return $response;
        }

        $this->requestMergeWithRow();

        $this->meta('title', $this->row->name);

        return $this->page('wallet.update', [
            'row' => $this->row,
            'products' => $this->products(),
            'platforms' => $this->platforms(),
            'orders' => $this->orders(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function products(): Collection
    {
        return ProductModel::query()
            ->byPlatformId($this->row->platform_id)
            ->orderBy('acronym', 'ASC')
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function platforms(): Collection
    {
        return PlatformModel::query()
            ->byUserId($this->auth->id)
            ->list()
            ->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function orders(): Collection
    {
        return OrderModel::query()
            ->byProductId($this->row->product_id)
            ->byUserId($this->auth->id)
            ->whereFilled()
            ->list()
            ->get();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|false|null
     */
    protected function actions(): RedirectResponse|false|null
    {
        return $this->actionPost('update')
            ?: $this->actionPost('delete')
            ?: $this->actionIfExists('updateSync');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(): RedirectResponse
    {
        return redirect()->route('wallet.update', $this->action()->update()->id);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function updateSync(): RedirectResponse
    {
        return redirect()->route('wallet.update', $this->action()->updateSync()->id);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function delete(): RedirectResponse
    {
        $this->action()->delete();

        return redirect()->route('wallet.index');
    }
}
