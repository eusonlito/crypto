<?php declare(strict_types=1);

namespace App\Domains\Wallet\Controller;

use Illuminate\Http\RedirectResponse;

class UpdateSellStopLoss extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): RedirectResponse
    {
        $this->row($id);

        $this->actionPost('updateSellStopLoss');

        return redirect()->back();
    }

    /**
     * @return void
     */
    protected function updateSellStopLoss(): void
    {
        $this->action()->updateSellStopLoss();
    }
}
