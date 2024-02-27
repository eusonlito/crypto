<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Platform\Model\Platform as PlatformModel;

class Create extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($response = $this->actionPost('create')) {
            return $response;
        }

        $this->meta('title', __('wallet-create.meta-title'));

        return $this->page('wallet.create', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        $data = ['platforms' => PlatformModel::query()->list()->get()];

        if ($platform_id = $this->request->input('platform_id')) {
            $data['products'] = ProductModel::query()->byPlatformId($platform_id)->orderBy('acronym', 'ASC')->get();
        } else {
            $data['products'] = null;
        }

        return $data;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function create(): RedirectResponse
    {
        return redirect()->route('wallet.update', $this->action()->create()->id);
    }
}
