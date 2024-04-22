<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Domains\Platform\Model\Platform as PlatformModel;

class Sync extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(): Response|RedirectResponse
    {
        if ($this->auth->plarformsPivot()->count() === 0) {
            return redirect()->route('user.update.platform');
        }

        if ($response = $this->actionPost('sync')) {
            return $response;
        }

        $this->meta('title', __('dashboard-sync.meta-title'));

        return $this->page('dashboard.sync', [
            'platforms' => PlatformModel::query()->byUserId($this->auth->id)->list()->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sync(): RedirectResponse
    {
        $this->action()->sync();

        return redirect()->route('dashboard.index');
    }
}
