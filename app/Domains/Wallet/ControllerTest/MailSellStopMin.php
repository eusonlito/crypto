<?php declare(strict_types=1);

namespace App\Domains\Wallet\ControllerTest;

use App\Domains\Wallet\Mail\SellStopMin;

class MailSellStopMin extends ControllerTestAbstract
{
    /**
     * @return \App\Domains\Wallet\Mail\SellStopMin
     */
    public function __invoke(): SellStopMin
    {
        return $this->factory()->mail()->sellStopMin(
            $this->rowLast(),
            $this->row->toObject(),
            $this->row->orders()->orderByLast()->first(),
        );
    }
}
