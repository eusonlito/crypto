<?php declare(strict_types=1);

namespace App\Domains\Order\Mail;

use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;

class Filled extends MailAbstract
{
    /**
     * @var \App\Domains\Order\Model\Order
     */
    public Model $row;

    /**
     * @var \Illuminate\Support\Collection
     */
    public Collection $previous;

    /**
     * @var string
     */
    public $view = 'domains.order.mail.filled';

    /**
     * @param \App\Domains\Order\Model\Order $row
     * @param \Illuminate\Support\Collection $previous
     *
     * @return self
     */
    public function __construct(Model $row, Collection $previous)
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
        $this->previous = $previous;
    }
}
