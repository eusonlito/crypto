<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;

class UpdateBuyStop extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        $this->row($id);

        $this->actionPost('updateBuyStop');

        return redirect()->back();
    }

    /**
     * @return void
     */
    protected function updateBuyStop(): void
    {
        $this->action()->updateBuyStop();
    }
}
