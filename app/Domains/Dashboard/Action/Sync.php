<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Action;

class Sync extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->syncOrder();
        $this->syncWallet();
        $this->syncOrder();
    }

    /**
     * @return void
     */
    protected function syncOrder(): void
    {
        $this->factory('Order')->action()->syncAll();
    }

    /**
     * @return void
     */
    protected function syncWallet(): void
    {
        $this->factory('Wallet')->action()->syncAll();
    }
}
