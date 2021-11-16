<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;
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
    public function __invoke(int $id)
    {
        $this->row($id);

        if ($response = $this->actions()) {
            return $response;
        }

        $this->meta('title', $this->row->name);

        return $this->page('wallet.update', [
            'row' => $this->row,
            'products' => ProductModel::byPlatformId($this->row->platform_id)->list()->get(),
            'platforms' => PlatformModel::list()->get(),
            'orders' => OrderModel::byProductId($this->row->product_id)->whereFilled()->list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|false|null
     */
    protected function actions()
    {
        return $this->actionPost('update')
            ?: $this->actionPost('delete')
            ?: $this->actionIfExists('syncOne');
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
    protected function syncOne(): RedirectResponse
    {
        return redirect()->route('wallet.update', $this->action()->syncOne()->id);
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
