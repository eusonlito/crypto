<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\RedirectResponse;

class AuthCredentials extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($response = $this->actionPost('authCredentials')) {
            return $response;
        }

        $this->meta('title', __('user-auth-credentials.meta-title'));

        return $this->page('user.auth-credentials');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authCredentials(): RedirectResponse
    {
        $this->action()->authCredentials();

        return redirect()->route('dashboard.index');
    }
}
