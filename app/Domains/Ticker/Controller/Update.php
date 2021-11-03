<?php declare(strict_types=1);

namespace App\Domains\Ticker\Controller;

use Illuminate\Http\RedirectResponse;
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

        $this->meta('title', $this->row->product->name);

        return $this->page('ticker.update', [
            'row' => $this->row,
            'products' => ProductModel::byPlatformId($this->row->platform_id)->list()->get(),
            'platforms' => PlatformModel::list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|false|null
     */
    protected function actions()
    {
        return $this->actionPost('update') ?: $this->actionPost('delete');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(): RedirectResponse
    {
        return redirect()->route('ticker.update', $this->action()->update()->id);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function delete(): RedirectResponse
    {
        $this->action()->delete();

        return redirect()->route('ticker.index');
    }
}
