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

    <tr>
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Fecha</th>
                            <td style="font-size: 0.8rem">@datetime($row->created_at)</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td style="font-size: 0.9rem">{{ str_replace('_', ' ', $row->type) }}</td>
                        </tr>
                        <tr>
                            <th>Modo</th>
                            <td>{{ $row->side }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <td>@number($row->amount)</td>
                        </tr>
                        <tr>
                            <th>Cambio</th>
                            <td>@number($row->price)</td>
                        </tr>
                        <tr>
                            <th>Valor</th>
                            <td>@number($row->value)</td>
                        </tr>

                        @if ($next = $previous->first())

                        <tr>
                            <th>Diferencia</th>
                            <td>@number($row->value - ($row->amount * $next->price))</td>
                        </tr>

                        @endif
                    </table>
                </div>
            </div>
        </td>
    </tr>

    @if ($previous->isNotEmpty())

    <tr>
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <h3>Anteriores</h3>
                </div>
            </div>
        </td>
    </tr>

    @foreach ($previous as $row)

    <tr>
        <td valign="middle">
            <div class="product-entry bg_white">
                <div class="text">
                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Fecha</th>
                            <td style="font-size: 0.8rem">@datetime($row->created_at)</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td style="font-size: 0.9rem">{{ str_replace('_', ' ', $row->type) }}</td>
                        </tr>
                        <tr>
                            <th>Modo</th>
                            <td>{{ $row->side }}</td>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <td>@number($row->amount)</td>
                        </tr>
                        <tr>
                            <th>Cambio</th>
                            <td>@number($row->price)</td>
                        </tr>
                        <tr>
                            <th>Valor</th>
                            <td>@number($row->value)</td>
                        </tr>

                        @if ($next = $previous->get($loop->index + 1))

                        <tr>
                            <th>Diferencia</th>
                            <td>@number($row->value - ($row->amount * $next->price))</td>
                        </tr>

                        @endif
                    </table>
                </div>
            </div>
        </td>
    </tr>

    @endforeach

    @endif
</table>
