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
                            <th>Tipo</th>
                            <th>Modo</th>
                            <th>Cantidad</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td>@datetime($row->created_at)</td>
                            <td>{{ $row->type }}</td>
                            <td>{{ $row->side }}</td>
                            <td>@number($row->amount)</td>
                            <td>@number($row->price)</td>
                            <td>@number($row->value)</td>
                        </tr>
                    </table>

                    @if ($previous->isNotEmpty())

                    <h3>Anteriores</h3>

                    <table class="table" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Modo</th>
                            <th>Cantidad</th>
                            <th>Cambio</th>
                            <th>Valor</th>
                        </tr>

                        @foreach ($previous as $row)

                        <tr>
                            <td>@datetime($row->created_at)</td>
                            <td>{{ $row->type }}</td>
                            <td>{{ $row->side }}</td>
                            <td>@number($row->amount)</td>
                            <td>@number($row->price)</td>
                            <td>@number($row->value)</td>
                        </tr>

                        @endforeach
                    </table>

                    @endif
                </div>
            </div>
        </td>
    </tr>
</table>
