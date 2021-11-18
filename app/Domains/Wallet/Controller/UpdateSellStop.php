<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;

class UpdateSellStop extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        $this->row($id);

        $this->actionPost('updateSellStop');

        return redirect()->back();
    }

    /**
     * @return void
     */
    protected function updateSellStop(): void
    {
        $this->action()->updateSellStop();
    }
}
