@extends ('mail.layout')

@section ('body')

<tr>
    <td style="text-align: center; width: 50%">
        <div class="text-author">
            <h3 class="name"><a href="{{ route('wallet.update', $row->id) }}">Wallet {{ $row->name }}</a></h3>
            <p><strong>Platform</strong><br />{{ $row->platform->name }}</p>
            <p><strong>Amount</strong><br />@number($row->amount)</p>
            <p><strong>Exchange</strong><br />@number($row->buy_exchange)</p>
            <p><strong>Value</strong><br />@number($row->buy_value)</p>
        </div>
    </td>

    <td style="text-align: center; width: 50%">
        <div class="text-author">
            <p><strong>@datetime($order->created_at)</strong></p>
            <p><strong>Amount</strong><br />@number($order->amount)</p>
            <p><strong>Price</strong><br />@number($order->price)</p>
            <p><strong>Price Stop</strong><br />@number($order->price_stop)</p>
            <p><strong>Value</strong><br />@number($order->value)</p>
        </div>
    </td>
</tr>

@stop