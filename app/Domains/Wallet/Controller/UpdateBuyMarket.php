<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;

class UpdateBuyMarket extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        $this->row($id);

        $this->actionPost('updateBuyMarket');

        return redirect()->back();
    }

    /**
     * @return void
     */
    protected function updateBuyMarket(): void
    {
        $this->action()->updateBuyMarket();
    }
}
