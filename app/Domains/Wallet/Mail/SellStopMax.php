<?php declare(strict_types=1);

namespace App\Domains\Wallet\Mail;

use App\Domains\Order\Model\Order as OrderModel;
use App\Domains\Wallet\Model\Wallet as Model;

class SellStopMax extends MailAbstract
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    public Model $row;

    /**
     * @var \App\Domains\Order\Model\Order
     */
    public OrderModel $order;

    /**
     * @var string
     */
    public $view = 'domains.wallet.mail.sell-stop-max';

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \App\Domains\Order\Model\Order $order
     *
     * @return self
     */
    public function __construct(Model $row, OrderModel $order)
    {
        $this->to($row->user->email);

        $this->subject = __('wallet-sell-stop-max-mail.subject', [
            'platform' => $row->platform->name,
            'name' => $row->product->acronym,
            'amount' => round($order->amount, $row->product->quantity_decimal),
            'price' => round($order->price, $row->product->price_decimal),
            'value' => round($order->value, $row->product->price_decimal),
        ]);

        $this->row = $row;
        $this->order = $order;
    }
}
