@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="id" value="id" :text="['product.name', 'platform.name']" :options="$list->toArray()" :placeholder="__('wallet-percent.wallet-placeholder')" :selected="$REQUEST->input('id')" data-change-submit required></x-select>
    </form>
</div>

@if (isset($row))

<form method="post" data-change-event-change>
    <input type="hidden" name="_action" value="percent" />

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 mb-2 lg:col-span-6">
                <label for="wallet-address" class="form-label">{{ __('wallet-percent.address') }}</label>
                <input type="text" name="address" class="form-control form-control-lg" id="wallet-address" value="{{ $row->address }}" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-6">
                <label for="wallet-name" class="form-label">{{ __('wallet-percent.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" id="wallet-name" value="{{ $row->name }}" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-amount" class="form-label">{{ __('wallet-percent.amount') }}</label>
                <input type="number" name="amount" step="0.000000001" class="form-control form-control-lg" id="wallet-amount" value="@numberString($REQUEST->input('amount'))">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-percent.buy_exchange') }}</label>
                <input type="number" name="buy_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_exchange" value="@numberString($REQUEST->input('buy_exchange'))">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-current_exchange" class="form-label">{{ __('wallet-percent.current_exchange') }}</label>
                <input type="number" name="current_exchange" class="form-control form-control-lg" id="wallet-current_exchange" value="@numberString($REQUEST->input('current_exchange'))" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_value" class="form-label">{{ __('wallet-percent.buy_value') }}</label>
                <input type="number" name="buy_value" class="form-control form-control-lg" id="wallet-buy_value" value="@numberString($REQUEST->input('buy_value'))" data-total data-total-amount="wallet-amount" data-total-value="wallet-buy_exchange" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-current_value" class="form-label">{{ __('wallet-percent.current_value') }}</label>
                <input type="number" name="current_value" class="form-control form-control-lg" id="wallet-current_value" value="@numberString($REQUEST->input('current_value'))" data-total data-total-amount="wallet-amount" data-total-value="wallet-current_exchange" readonly>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_amount" class="form-label">{{ __('wallet-update.sell_stop_amount') }}</label>
                <input type="number" name="sell_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_amount" value="@numberString($REQUEST->input('sell_stop_amount'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_percent" class="form-label">{{ __('wallet-update.sell_stop_max_percent') }}</label>
                <input type="number" name="sell_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_max_percent" value="@value($REQUEST->input('sell_stop_max_percent'), 2)" data-percent-to-value="wallet-sell_stop_max" data-percent-to-value-reference="wallet-buy_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_percent" class="form-label">{{ __('wallet-update.sell_stop_min_percent') }}</label>
                <input type="number" name="sell_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_min_percent" value="@value($REQUEST->input('sell_stop_min_percent'), 2)" data-percent-to-value="wallet-sell_stop_min" data-percent-to-value-reference="wallet-sell_stop_max" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max" class="form-label">{{ __('wallet-update.sell_stop_max') }}</label>
                <input type="number" name="sell_stop_max" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max" value="@numberString($REQUEST->input('sell_stop_max'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min" class="form-label">{{ __('wallet-update.sell_stop_min') }}</label>
                <input type="number" name="sell_stop_min" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min" value="@numberString($REQUEST->input('sell_stop_min'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_value" class="form-label">{{ __('wallet-update.sell_stop_max_value') }}</label>
                <input type="number" name="sell_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max_value" value="@numberString($REQUEST->input('sell_stop_max_value'))" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_max" data-total-change="wallet-sell_stop_max_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_value" class="form-label">{{ __('wallet-update.sell_stop_min_value') }}</label>
                <input type="number" name="sell_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min_value" value="@numberString($REQUEST->input('sell_stop_min_value'))" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_min" data-total-change="wallet-sell_stop_min_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop" value="1" class="form-check-switch" id="wallet-sell_stop" {{ $REQUEST->input('sell_stop') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop" class="form-check-label">{{ __('wallet-update.sell_stop') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_amount" class="form-label">{{ __('wallet-update.buy_stop_amount') }}</label>
                <input type="number" name="buy_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_amount" value="@numberString($REQUEST->input('buy_stop_amount'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_percent" class="form-label">{{ __('wallet-update.buy_stop_min_percent') }}</label>
                <input type="number" name="buy_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_min_percent" value="@value($REQUEST->input('buy_stop_min_percent'), 2)" data-percent-to-value="wallet-buy_stop_min" data-percent-to-value-reference="wallet-buy_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_percent" class="form-label">{{ __('wallet-update.buy_stop_max_percent') }}</label>
                <input type="number" name="buy_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_max_percent" value="@value($REQUEST->input('buy_stop_max_percent'), 2)" data-percent-to-value="wallet-buy_stop_max" data-percent-to-value-reference="wallet-buy_stop_min">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min" class="form-label">{{ __('wallet-update.buy_stop_min') }}</label>
                <input type="number" name="buy_stop_min" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min" value="@numberString($REQUEST->input('buy_stop_min'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max" class="form-label">{{ __('wallet-update.buy_stop_max') }}</label>
                <input type="number" name="buy_stop_max" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max" value="@numberString($REQUEST->input('buy_stop_max'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_value" class="form-label">{{ __('wallet-update.buy_stop_min_value') }}</label>
                <input type="number" name="buy_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min_value" value="@numberString($REQUEST->input('buy_stop_min_value'))" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_min" data-total-change="wallet-buy_stop_min_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_value" class="form-label">{{ __('wallet-update.buy_stop_max_value') }}</label>
                <input type="number" name="buy_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max_value" value="@numberString($REQUEST->input('buy_stop_max_value'))" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_max" data-total-change="wallet-buy_stop_max_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="wallet-buy_stop" {{ $REQUEST->input('buy_stop') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop" class="form-check-label">{{ __('wallet-update.buy_stop') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_percent" class="form-label">{{ __('wallet-update.sell_stoploss_percent') }}</label>
                <input type="number" name="sell_stoploss_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stoploss_percent" value="@value($REQUEST->input('sell_stoploss_percent'), 2)" data-percent-to-value="wallet-sell_stoploss_exchange" data-percent-to-value-reference="wallet-buy_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_exchange" class="form-label">{{ __('wallet-update.sell_stoploss_exchange') }}</label>
                <input type="number" name="sell_stoploss_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_exchange" value="@numberString($REQUEST->input('sell_stoploss_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_value" class="form-label">{{ __('wallet-update.sell_stoploss_value') }}</label>
                <input type="number" name="sell_stoploss_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_value" value="@numberString($REQUEST->input('sell_stoploss_value'))" data-total data-total-amount="wallet-amount" data-total-value="wallet-sell_stoploss_exchange" data-total-change="wallet-sell_stoploss_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stoploss" value="1" class="form-check-switch" id="wallet-sell_stoploss" {{ $REQUEST->input('sell_stoploss') ? 'checked' : '' }}>
                    <label for="wallet-sell_stoploss" class="form-check-label">{{ __('wallet-update.sell_stoploss') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('wallet-percent.calculate') }}</button>
        </div>
    </div>
</form>

<script>

function orderTooltip (data) {
    return [
        'Action: ' + data.action,
        'Current Exchange: ' + data.exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Sell-Stop Min Exchange: ' + data.wallet_sell_stop_min.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Sell-Stop Max Exchange: ' + data.wallet_sell_stop_max.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Buy-Stop Min Exchange: ' + data.wallet_buy_stop_min.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Buy-Stop Max Exchange: ' + data.wallet_buy_stop_max.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Buy-Stop-Loss Exchange: ' + data.wallet_sell_stoploss_exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        })
    ];
}

var charts = new Array();

charts.push({
    id: 'line-chart-{{ $row->id }}',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json($exchanges->keys()),

            datasets: [
                {
                    order: 10,

                    type: 'line',
                    label: 'Current Exchange',
                    backgroundColor: 'rgba(157, 157, 157, 0.7)',
                    borderColor: 'rgba(157, 157, 157, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($exchanges->values()),

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.raw.average.toLocaleString('es-ES', {
                                    minimumFractionDigits: {{ $row->product->price_decimal }}
                                });
                            }
                        }
                    },

                    parsing: {
                        xAxisKey: 'datetime',
                        yAxisKey: 'average'
                    },
                },

                @if ($orders->isNotEmpty())

                {
                    order: 1,

                    type: 'scatter',
                    label: 'Sell Stop Min',

                    pointRadius: 5,
                    backgroundColor: 'rgba(62, 187, 42, 1)',

                    data: @json($orders->where('action', 'sell-stop-min')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 2,

                    type: 'scatter',
                    label: 'Sell Stop Max',

                    pointRadius: 3,
                    backgroundColor: 'rgba(62, 187, 42, 0.3)',

                    data: @json($orders->where('action', 'sell-stop-max')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 3,

                    type: 'scatter',
                    label: 'Buy Stop Max',

                    pointRadius: 5,
                    backgroundColor: 'rgba(0, 0, 255, 1)',

                    data: @json($orders->where('action', 'buy-stop-max')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 4,

                    type: 'scatter',
                    label: 'Buy Stop Min',

                    pointRadius: 3,
                    backgroundColor: 'rgba(0, 0, 255, 0.3)',

                    data: @json($orders->where('action', 'buy-stop-min')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 4,

                    type: 'scatter',
                    label: 'Sell Stop Loss',

                    pointRadius: 5,
                    backgroundColor: 'rgba(199, 37, 37, 1)',

                    data: @json($orders->where('action', 'sell-stop-loss')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange'
                    },
                },

                @endif
            ]
        },

        options: {
            plugins: {
                legend: {
                    display: true
                },
            },

            scales: {
                x: {
                    ticks: {
                        fontSize: '12',
                        fontColor: '#777777',
                        autoSkip: true
                    },

                    grid: {
                        display: false
                    },
                },
                y: {
                    ticks: {
                        fontSize: '12',
                        fontColor: '#777777',
                        callback: function(value) {
                            return value.toLocaleString('es-ES', {
                                minimumFractionDigits: {{ $row->product->price_decimal }}
                            });
                        }
                    },

                    grid: {
                        color: '#D8D8D8',
                        zeroLineColor: '#D8D8D8',
                        borderDash: [2, 2],
                        zeroLineBorderDash: [2, 2],
                        drawBorder: false
                    },
                }
            }
        }
    }
});
</script>

<div class="box p-5 mt-5">
    <canvas id="line-chart-{{ $row->id }}" height="140"></canvas>
</div>

@if ($orders->isNotEmpty())

<div class="box p-5 mt-5">
    <div class="lg:flex">
        <div class="flex-auto p-2">
            <label for="wallet-wallet_current_amount" class="form-label">{{ __('wallet-percent.wallet_last_amount') }}</label>
            <input type="number" step="0.000000001" class="form-control form-control-lg" id="wallet-wallet_current_amount" value="@numberString($row->amount)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_current_exchange" class="form-label">{{ __('wallet-percent.wallet_last_exchange') }}</label>
            <input type="number" step="0.0001" class="form-control form-control-lg" id="wallet-wallet_current_exchange" value="@numberString($row->buy_exchange)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_current_value" class="form-label">{{ __('wallet-percent.wallet_last_value') }}</label>
            <input type="text" step="0.000000001" class="form-control form-control-lg" id="wallet-wallet_current_value" value="@number($row->buy_value, 2)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_profit" class="form-label">{{ __('wallet-percent.wallet_profit') }}</label>
            <input type="text" step="0.000000001" class="form-control form-control-lg" id="wallet-wallet_profit" value="@number($orders->where('filled', true)->sum('profit'), 2)" readonly />
        </div>
    </div>
</div>

<div class="box p-5 mt-5">
    <form method="get">
        <input type="search" class="form-control form-control-lg" placeholder="{{ __('wallet-percent.filter') }}" data-table-search="#wallet-percent-table" />
    </form>
</div>

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="wallet-percent-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('wallet-percent.order.date') }}</th>
                <th class="text-center">{{ __('wallet-percent.order.action') }}</th>
                <th>{{ __('wallet-percent.order.amount') }}</th>
                <th>{{ __('wallet-percent.order.exchange') }}</th>
                <th>{{ __('wallet-percent.order.value') }}</th>
                <th>{{ __('wallet-percent.order.buy_value') }}</th>
                <th>{{ __('wallet-percent.order.sell_stop_max') }}</th>
                <th>{{ __('wallet-percent.order.sell_stop_min') }}</th>
                <th>{{ __('wallet-percent.order.buy_stop_min') }}</th>
                <th>{{ __('wallet-percent.order.buy_stop_max') }}</th>
                <th>{{ __('wallet-percent.order.sell_stoploss_exchange') }}</th>
                <th>{{ __('wallet-percent.order.profit') }}</th>
                <th class="text-center">{{ __('wallet-percent.order.filled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $each)

            <tr class="text-right">
                <td><span class="block text-center" title="{{ $each->created_at }}">@datetime($each->created_at)</span></td>
                <td><span class="block text-center whitespace-nowrap">{{ $each->action }}</span></td>
                <td><span class="block" title="{{ $each->amount }}">@number($each->amount)</span></td>
                <td><span class="block" title="{{ $each->exchange }}">@number($each->exchange)</span></td>
                <td><span class="block" title="{{ $each->value }}">@number($each->value)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_value }}">@number($each->wallet_buy_value)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stop_max }}">@number($each->wallet_sell_stop_max)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stop_min }}">@number($each->wallet_sell_stop_min)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_stop_min }}">@number($each->wallet_buy_stop_min)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_stop_max }}">@number($each->wallet_buy_stop_max)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stoploss_exchange }}">@number($each->wallet_sell_stoploss_exchange)</span></td>
                <td><span class="block @numberColor($each->profit, $each->filled)" title="{{ $each->profit }}">@number($each->profit)</span></td>
                <td>
                    <span class="hidden">{{ $each->filled ? 'Completada' : '' }}</span>
                    <span class="block text-center">@status($each->filled)</span>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endif

@endif

@stop
