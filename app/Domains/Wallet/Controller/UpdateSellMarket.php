<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;

class UpdateSellMarket extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        $this->row($id);

        $this->actionPost('updateSellMarket');

        return redirect()->back();
    }

    /**
     * @return void
     */
    protected function updateSellMarket(): void
    {
        $this->action()->updateSellMarket();
    }
}
