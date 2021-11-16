<?php declare(strict_types=1);

namespace App\Domains\Wallet\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Domains\Shared\Mail\MailAbstract as MailAbstractShared;

abstract class MailAbstract extends MailAbstractShared implements ShouldQueue
{
}
