@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="id" value="id" :text="['product.name', 'platform.name']" :options="$list->toArray()" :placeholder="__('wallet-simulator.wallet-placeholder')" data-change-submit required></x-select>
    </form>
</div>

@if (isset($row))

<form method="post" data-change-event-change>
    <input type="hidden" name="_action" value="simulator" />

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 mb-2 lg:col-span-5">
                <label for="wallet-address" class="form-label">{{ __('wallet-simulator.address') }}</label>
                <input type="text" name="address" class="form-control form-control-lg" id="wallet-address" value="{{ $row->address }}" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-5">
                <label for="wallet-name" class="form-label">{{ __('wallet-simulator.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" id="wallet-name" value="{{ $row->name }}" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label class="form-label hidden xl:block">&nbsp;</label>

                <a href="{{ route('wallet.update', $row->id) }}" class="btn form-select-lg block" title="{{ __('wallet-simulator.edit') }}">
                    @icon('edit')
                </a>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-amount" class="form-label">{{ __('wallet-simulator.amount') }}</label>
                <input type="number" step="any" name="amount" class="form-control form-control-lg" id="wallet-amount" value="@numberString($amount)">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-simulator.buy_exchange') }}</label>
                <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="wallet-buy_exchange" value="@numberString($buy_exchange)">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-current_exchange" class="form-label">{{ __('wallet-simulator.current_exchange') }}</label>
                <input type="number" step="any" name="current_exchange" class="form-control form-control-lg" id="wallet-current_exchange" value="@numberString($current_exchange)" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_value" class="form-label">{{ __('wallet-simulator.buy_value') }}</label>
                <input type="number" step="any" name="buy_value" class="form-control form-control-lg" id="wallet-buy_value" value="@numberString($buy_value)" data-total data-total-amount="wallet-amount" data-total-value="wallet-buy_exchange" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-current_value" class="form-label">{{ __('wallet-simulator.current_value') }}</label>
                <input type="number" step="any" name="current_value" class="form-control form-control-lg" id="wallet-current_value" value="@numberString($current_value)" data-total data-total-amount="wallet-amount" data-total-value="wallet-current_exchange" readonly>
            </div>
        </div>
    </div>

    @include ('domains.wallet.molecules.create-update-crypto')

    <div class="box p-5 mt-5">
        <div class="p-4">
            <div class="form-check">
                <input type="checkbox" name="exchange_reverse" value="1" class="form-check-switch" id="wallet-exchange_reverse" {{ $exchange_reverse ? 'checked' : '' }}>
                <label for="wallet-exchange_reverse" class="form-check-label">{{ __('wallet-simulator.exchange_reverse') }}</label>
            </div>
        </div>

        <div class="p-4">
            <div class="form-check">
                <input type="checkbox" name="exchange_first" value="1" class="form-check-switch" id="wallet-exchange_first" {{ $exchange_first ? 'checked' : '' }}>
                <label for="wallet-exchange_first" class="form-check-label">{{ __('wallet-simulator.exchange_first') }}</label>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="p-4">
            <x-exchange-select name="time" :selected="$time" reverse data-change-submit></x-exchange-select>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('wallet-simulator.calculate') }}</button>
        </div>
    </div>
</form>

@if (isset($exchanges))

<script>

function orderTooltip (data) {
    return [
        'Action: ' + data.action,
        'Exchange: ' + data.exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Sell-Stop Min Exchange: ' + data.wallet_sell_stop_min_exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Buy-Stop Max Exchange: ' + data.wallet_buy_stop_max_exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        }),
        'Buy-Stop-Loss Exchange: ' + data.wallet_sell_stoploss_exchange.toLocaleString('es-ES', {
            minimumFractionDigits: {{ $row->product->price_decimal }}
        })
    ];
}

const charts = new Array();

charts.push({
    id: 'line-chart-{{ $row->id }}',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json(array_keys($exchanges)),

            datasets: [
                {
                    order: 10,

                    type: 'line',
                    label: 'Exchange',
                    backgroundColor: 'rgba(157, 157, 157, 0.7)',
                    borderColor: 'rgba(157, 157, 157, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_values($exchanges)),

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.raw.toLocaleString('es-ES', {
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

                    data: @json($orders->where('action', 'sell_stop')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'created_at',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 3,

                    type: 'scatter',
                    label: 'Buy Stop Max',

                    pointRadius: 5,
                    backgroundColor: 'rgba(0, 0, 255, 1)',

                    data: @json($orders->where('action', 'buy_stop')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'created_at',
                        yAxisKey: 'exchange'
                    },
                },

                {
                    order: 4,

                    type: 'scatter',
                    label: 'Sell Stop Loss',

                    pointRadius: 5,
                    backgroundColor: 'rgba(199, 37, 37, 1)',

                    data: @json($orders->where('action', 'sell_stoploss')->values()),

                    tooltip: {
                        callbacks: {
                            label: (context) => orderTooltip(context.raw),
                        }
                    },

                    parsing: {
                        xAxisKey: 'created_at',
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

<div class="box p-5 mt-5">
    <div class="lg:flex">
        <div class="flex-auto p-2">
            <label for="wallet-wallet_start_amount" class="form-label">{{ __('wallet-simulator.wallet_start_amount') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_start_amount" value="@numberString($amount)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_end_amount" class="form-label">{{ __('wallet-simulator.wallet_end_amount') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_end_amount" value="@numberString($rowResult->amount)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_start_exchange" class="form-label">{{ __('wallet-simulator.wallet_start_exchange') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_start_exchange" value="@numberString($exchangeFirst)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_end_exchange" class="form-label">{{ __('wallet-simulator.wallet_end_exchange') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_end_exchange" value="@numberString($exchangeLast)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_start_value" class="form-label">{{ __('wallet-simulator.wallet_start_value') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_start_value" value="@number($amount * $exchangeFirst, 2)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_end_value" class="form-label">{{ __('wallet-simulator.wallet_end_value') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_end_value" value="@number($rowResult->amount * $exchangeLast, 2)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_profit" class="form-label">{{ __('wallet-simulator.wallet_profit') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_profit" value="@number($profit, 2)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-wallet_end_total" class="form-label">{{ __('wallet-simulator.wallet_end_total') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-wallet_end_total" value="@number(($rowResult->amount * $exchangeLast) + $profit, 2)" readonly />
        </div>
    </div>
</div>

<div class="box p-5 mt-5">
    <div class="lg:flex">
        <div class="flex-auto p-2">
            <label for="wallet-orders_count" class="form-label">{{ __('wallet-simulator.orders_count') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_count" value="@number($orders->count(), 0)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-orders_buy" class="form-label">{{ __('wallet-simulator.orders_buy') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_buy" value="@number($ordersBuy->count(), 0)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-orders_sell" class="form-label">{{ __('wallet-simulator.orders_sell') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_sell" value="@number($ordersSell->count(), 0)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-orders_buy_value" class="form-label">{{ __('wallet-simulator.orders_buy_value') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_buy_value" value="@number($ordersBuyValue)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-orders_sell_value" class="form-label">{{ __('wallet-simulator.orders_sell_value') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_sell_value" value="@number($ordersSellValue)" readonly />
        </div>

        <div class="flex-auto p-2">
            <label for="wallet-orders_difference" class="form-label">{{ __('wallet-simulator.orders_difference') }}</label>
            <input type="text" class="form-control form-control-lg" id="wallet-orders_difference" value="@number($ordersSellValue - $ordersBuyValue)" readonly />
        </div>
    </div>
</div>

<div class="box p-5 mt-5">
    <form method="get">
        <input type="search" class="form-control form-control-lg" placeholder="{{ __('wallet-simulator.filter') }}" data-table-search="#wallet-simulator-table" />
    </form>
</div>

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="wallet-simulator-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('wallet-simulator.order.date') }}</th>
                <th class="text-center">{{ __('wallet-simulator.order.action') }}</th>
                <th>{{ __('wallet-simulator.order.amount') }}</th>
                <th>{{ __('wallet-simulator.order.exchange') }}</th>
                <th>{{ __('wallet-simulator.order.value') }}</th>
                <th>{{ __('wallet-simulator.order.buy_value') }}</th>
                <th>{{ __('wallet-simulator.order.sell_stop_max_exchange') }}</th>
                <th>{{ __('wallet-simulator.order.sell_stop_min_exchange') }}</th>
                <th>{{ __('wallet-simulator.order.buy_stop_min_exchange') }}</th>
                <th>{{ __('wallet-simulator.order.buy_stop_max_exchange') }}</th>
                <th>{{ __('wallet-simulator.order.sell_stoploss_exchange') }}</th>
                <th>{{ __('wallet-simulator.order.profit') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $each)

            <tr class="text-right">
                <td><span class="block text-center whitespace-nowrap" title="{{ $each->created_at }}">@datetime($each->created_at)</span></td>
                <td><span class="block text-center whitespace-nowrap">{{ $each->action }}</span></td>
                <td><span class="block" title="{{ $each->amount }}">@number($each->amount)</span></td>
                <td><span class="block" title="{{ $each->exchange }}">@number($each->exchange)</span></td>
                <td><span class="block" title="{{ $each->value }}">@number($each->value)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_value }}">@number($each->wallet_buy_value)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stop_max_exchange }}">@number($each->wallet_sell_stop_max_exchange)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stop_min_exchange }}">@number($each->wallet_sell_stop_min_exchange)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_stop_min_exchange }}">@number($each->wallet_buy_stop_min_exchange)</span></td>
                <td><span class="block" title="{{ $each->wallet_buy_stop_max_exchange }}">@number($each->wallet_buy_stop_max_exchange)</span></td>
                <td><span class="block" title="{{ $each->wallet_sell_stoploss_exchange }}">@number($each->wallet_sell_stoploss_exchange)</span></td>
                <td><span class="block @numberColor($each->profit, true)" title="{{ $each->profit }}">@number($each->profit)</span></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endif

@endif

@stop
