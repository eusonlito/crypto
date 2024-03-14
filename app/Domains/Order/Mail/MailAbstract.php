<?php declare(strict_types=1);

namespace App\Domains\Order\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domains\Core\Mail\MailAbstract as MailAbstractCore;

abstract class MailAbstract extends MailAbstractCore implements ShouldQueue
{
}
