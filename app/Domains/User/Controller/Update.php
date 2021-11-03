<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Http\RedirectResponse;
use App\Domains\User\Service\TFA\Google as GoogleTFA;

class Update extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        $this->rowAuth();

        if ($response = $this->actionPost('update')) {
            return $response;
        }

        $this->requestMergeWithRow();

        $this->meta('title', __('user-update.meta-title'));

        return $this->page('user.update', [
            'row' => $this->auth,
            'tfa_qr' => GoogleTFA::getQRCodeInline($this->row->email, $this->row->tfa_secret),
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(): RedirectResponse
    {
        $this->action($this->auth)->update();

        service()->message()->success(__('user-update.success'));

        return redirect()->route('user.update');
    }
}
