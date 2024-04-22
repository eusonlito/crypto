<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use App\Domains\Order\Model\Order as Model;

class WalletFix extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->update();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        Model::walletFix();
    }
}
