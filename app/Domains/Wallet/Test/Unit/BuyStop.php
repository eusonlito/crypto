<?php declare(strict_types=1);

namespace App\Domains\Wallet\Test\Unit;

class BuyStop extends UnitAbstract
{
    /**
     * @return void
     */
    public function testSuccess(): void
    {
        $this->authUser();

        $this->setCurl();
    }

    /**
     * @return void
     */
    protected function setCurl(): void
    {
        $this->curlFake('resources/app/test/wallet/buy-stop.log');
    }
}
