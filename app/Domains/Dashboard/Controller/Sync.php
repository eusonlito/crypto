<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use Illuminate\Http\RedirectResponse;

class Sync extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($this->auth->plarformsPivot()->count() === 0) {
            return redirect()->route('user.update.platform');
        }

        if ($response = $this->actionPost('sync')) {
            return $response;
        }

        $this->meta('title', __('dashboard-sync.meta-title'));

        return $this->page('dashboard.sync');
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
