<?php declare(strict_types=1);

namespace App\Domains\Order\ControllerTest;

use App\Domains\Order\Mail\Filled;

class MailFilled extends ControllerTestAbstract
{
    /**
     * @return \App\Domains\Order\Mail\Filled
     */
    public function __invoke(): Filled
    {
        return $this->factory()->mail()->filled(
            $this->rowLast(),
        );
    }
}
