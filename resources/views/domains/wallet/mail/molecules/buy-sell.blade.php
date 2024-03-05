<table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
        <td valign="middle" class="hero bg_white">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="padding: 2em;">
                        <div class="text">
                            <h2 style="margin-bottom: 0">{{ $subject }}</h2>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <h3>Cartera <a href="{{ route('wallet.update', $row->id) }}">{{ $row->product->acronym }}</a> en {{ $row->platform->name }}</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cantidad</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@number($row->amount)</td>
                            <td>@number($row->buy_exchange)</td>
                            <td>@number($row->buy_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Venta en Subida ({{ $row->sell_stop_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cambio Máximo</th>
                            <th>Valor Máximo</th>
                            <th>Cambio Mínimo</th>
                            <th>Valor Mínimo</th>
                        </tr>
                        <tr>
                            <td>@number($row->sell_stop_max_exchange) <small>@number($row->sell_stop_max_percent, 2)%</small></td>
                            <td>@number($row->sell_stop_max_value)</td>
                            <td>@number($row->sell_stop_min_exchange) <small>@number($row->sell_stop_min_percent, 2)%</small></td>
                            <td>@number($row->sell_stop_min_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Compra en Bajada ({{ $row->buy_stop_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cambio Mínimo</th>
                            <th>Valor Mínimo</th>
                            <th>Cambio Máximo</th>
                            <th>Valor Máximo</th>
                        </tr>
                        <tr>
                            <td>@number($row->buy_stop_min_exchange) <small>@number($row->buy_stop_min_percent, 2)%</small></td>
                            <td>@number($row->buy_stop_min_value)</td>
                            <td>@number($row->buy_stop_max_exchange) <small>@number($row->buy_stop_max_percent, 2)%</small></td>
                            <td>@number($row->buy_stop_max_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Compra en Subida ({{ $row->buy_market_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Referencia</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@number($row->buy_market_reference)</td>
                            <td>@number($row->buy_market_exchange) <small>@number($row->buy_market_percent, 2)%</small></td>
                            <td>@number($row->buy_market_value)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>

    @if (isset($previous))

    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <h3>Estado Anterior de <a href="{{ route('wallet.update', $row->id) }}">{{ $row->product->acronym }}</a> en {{ $row->platform->name }}</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cantidad</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@number($previous->amount)</td>
                            <td>@number($previous->buy_exchange)</td>
                            <td>@number($previous->buy_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Venta en Subida ({{ $previous->sell_stop_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cambio Máximo</th>
                            <th>Valor Máximo</th>
                            <th>Cambio Mínimo</th>
                            <th>Valor Mínimo</th>
                        </tr>
                        <tr>
                            <td>@number($previous->sell_stop_max_exchange) <small>@number($previous->sell_stop_max_percent, 2)%</small></td>
                            <td>@number($previous->sell_stop_max_value)</td>
                            <td>@number($previous->sell_stop_min_exchange) <small>@number($previous->sell_stop_min_percent, 2)%</small></td>
                            <td>@number($previous->sell_stop_min_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Compra en Bajada ({{ $previous->buy_stop_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Cambio Mínimo</th>
                            <th>Valor Mínimo</th>
                            <th>Cambio Máximo</th>
                            <th>Valor Máximo</th>
                        </tr>
                        <tr>
                            <td>@number($previous->buy_stop_min_exchange) <small>@number($previous->buy_stop_min_percent, 2)%</small></td>
                            <td>@number($previous->buy_stop_min_value)</td>
                            <td>@number($previous->buy_stop_max_exchange) <small>@number($previous->buy_stop_max_percent, 2)%</small></td>
                            <td>@number($previous->buy_stop_max_value)</td>
                        </tr>
                    </table>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <h3>Compra en Subida ({{ $previous->buy_market_amount }})</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Referencia</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@number($previous->buy_market_reference)</td>
                            <td>@number($previous->buy_market_exchange) <small>@number($previous->buy_market_percent, 2)%</small></td>
                            <td>@number($previous->buy_market_value)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>

    @endif

    <tr style="border-bottom: 1px solid rgba(0,0,0,.05);">
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <h3>Orden</h3>

                    <hr style="border: 1px solid rgba(0,0,0,.05);">

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@datetime($order->created_at)</td>
                            <td>@number($order->amount)</td>
                            <td>@number($order->price)</td>
                            <td>@number($order->value)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>
