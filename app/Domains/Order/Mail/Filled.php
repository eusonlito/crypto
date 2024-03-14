<?php declare(strict_types=1);

namespace App\Domains\Order\Mail;

use App\Domains\Order\Model\Order as Model;

class Filled extends MailAbstract
{
    /**
     * @var \App\Domains\Order\Model\Order
     */
    public Model $row;

    /**
     * @var string
     */
    public $view = 'domains.order.mail.filled';

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return self
     */
    public function __construct(Model $row)
    {
        $this->to($row->user->email);

        $this->subject = __('order-filled-mail.subject', [
            'side' => strtoupper($row->side),
            'platform' => $row->platform->name,
            'name' => $row->product->acronym,
            'amount' => $row->amount,
            'price' => $row->price,
        ]);

        $this->row = $row;
    }
}
