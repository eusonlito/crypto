<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\RedirectResponse;

class Signup extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($response = $this->actionPost('signup')) {
            return $response;
        }

        $this->meta('title', __('user-signup.meta-title'));

        return $this->page('user.signup');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function signup(): RedirectResponse
    {
        $this->action()->signup();

        return redirect()->route('dashboard.index');
    }
}
