<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\RedirectResponse;
use App\Domains\Platform\Model\Platform as PlatformModel;

class UpdatePlatform extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        $this->rowAuth();

        if ($response = $this->actionPost('updatePlatform')) {
            return $response;
        }

        $this->meta('title', __('user-update-platform.meta-title'));

        return $this->page('user.update-platform', [
            'platforms' => PlatformModel::list()->withUserPivot($this->auth->id)->get(),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function updatePlatform(): RedirectResponse
    {
        $this->action($this->auth)->updatePlatform();

        return redirect()->route('dashboard.index');
    }
}
