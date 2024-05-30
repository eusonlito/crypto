@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="id" value="id" :text="['product.name', 'platform.name']" :options="$list->toArray()" :placeholder="__('wallet-scenario.wallet-placeholder')" data-change-submit required></x-select>
    </form>
</div>

@if (isset($row))

<form method="post" data-wallet-scenario>
    <input type="hidden" name="_action" value="scenario" />

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-4">
                <label for="wallet-amount" class="form-label">{{ __('wallet-scenario.amount') }}</label>
                <input type="number" step="any" name="amount" class="form-control form-control-lg" id="wallet-amount" value="@numberString($amount)">
            </div>

            <div class="col-span-4">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-scenario.buy_exchange') }}</label>
                <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="wallet-buy_exchange" value="@numberString($buy_exchange)">
            </div>

            <div class="col-span-4">
                <label for="wallet-time" class="form-label">{{ __('wallet-scenario.time') }}</label>
                <x-exchange-select name="time" :selected="$time" reverse data-change-submit></x-exchange-select>
            </div>
        </div>
    </div>

    <div class="box mt-5">
        <div class="px-5 py-3 border-b border-gray-200">
            <h2 class="font-medium text-base">
                {{ __('wallet-scenario.buy_stop_title') }}
            </h2>
        </div>

        <div class="p-3">
            <div class="xl:flex">
                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_amount" class="form-label">{{ __('wallet-scenario.buy_stop_amount') }}</label>
                    <input type="number" step="any" name="buy_stop_amount" class="form-control form-control-lg" id="wallet-buy_stop_amount" value="@numberString($buy_stop_amount)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_min_percent_min" class="form-label">{{ __('wallet-scenario.buy_stop_min_percent_min') }}</label>
                    <input type="number" step="any" name="buy_stop_min_percent_min" class="form-control form-control-lg" id="wallet-buy_stop_min_percent_min" value="@value($buy_stop_min_percent_min, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_min_percent_max" class="form-label">{{ __('wallet-scenario.buy_stop_min_percent_max') }}</label>
                    <input type="number" step="any" name="buy_stop_min_percent_max" class="form-control form-control-lg" id="wallet-buy_stop_min_percent_max" value="@value($buy_stop_min_percent_max, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_max_percent_min" class="form-label">{{ __('wallet-scenario.buy_stop_max_percent_min') }}</label>
                    <input type="number" step="any" name="buy_stop_max_percent_min" class="form-control form-control-lg" id="wallet-buy_stop_max_percent_min" value="@value($buy_stop_max_percent_min, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_max_percent_max" class="form-label">{{ __('wallet-scenario.buy_stop_max_percent_max') }}</label>
                    <input type="number" step="any" name="buy_stop_max_percent_max" class="form-control form-control-lg" id="wallet-buy_stop_max_percent_max" value="@value($buy_stop_max_percent_max, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-buy_stop_percent_step" class="form-label">{{ __('wallet-scenario.buy_stop_percent_step') }}</label>
                    <input type="number" step="any" name="buy_stop_percent_step" class="form-control form-control-lg" id="wallet-buy_stop_percent_step" value="@value($buy_stop_percent_step, 2)">
                </div>
            </div>

            <div class="xl:flex">
                <div class="flex-initial p-4">
                    <div class="form-check">
                        <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="wallet-buy_stop" {{ $buy_stop ? 'checked' : '' }}>
                        <label for="wallet-buy_stop" class="form-check-label">{{ __('wallet-scenario.buy_stop') }}</label>
                    </div>
                </div>

                <div class="flex-initial p-4">
                    <div class="form-check">
                        <input type="checkbox" name="buy_stop_max_follow" value="1" class="form-check-switch" id="wallet-buy_stop_max_follow" {{ $buy_stop_max_follow ? 'checked' : '' }}>
                        <label for="wallet-buy_stop_max_follow" class="form-check-label">{{ __('wallet-scenario.buy_stop_max_follow') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box mt-5">
        <div class="px-5 py-3 border-b border-gray-200">
            <h2 class="font-medium text-base">
                {{ __('wallet-scenario.sell_stop_title') }}
            </h2>
        </div>

        <div class="p-3">
            <div class="xl:flex">
                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_amount" class="form-label">{{ __('wallet-scenario.sell_stop_amount') }}</label>
                    <input type="number" step="any" name="sell_stop_amount" class="form-control form-control-lg" id="wallet-sell_stop_amount" value="@numberString($sell_stop_amount)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_max_percent_min" class="form-label">{{ __('wallet-scenario.sell_stop_max_percent_min') }}</label>
                    <input type="number" step="any" name="sell_stop_max_percent_min" class="form-control form-control-lg" id="wallet-sell_stop_max_percent_min" value="@value($sell_stop_max_percent_min, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_max_percent_max" class="form-label">{{ __('wallet-scenario.sell_stop_max_percent_max') }}</label>
                    <input type="number" step="any" name="sell_stop_max_percent_max" class="form-control form-control-lg" id="wallet-sell_stop_max_percent_max" value="@value($sell_stop_max_percent_max, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_min_percent_min" class="form-label">{{ __('wallet-scenario.sell_stop_min_percent_min') }}</label>
                    <input type="number" step="any" name="sell_stop_min_percent_min" class="form-control form-control-lg" id="wallet-sell_stop_min_percent_min" value="@value($sell_stop_min_percent_min, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_min_percent_max" class="form-label">{{ __('wallet-scenario.sell_stop_min_percent_max') }}</label>
                    <input type="number" step="any" name="sell_stop_min_percent_max" class="form-control form-control-lg" id="wallet-sell_stop_min_percent_max" value="@value($sell_stop_min_percent_max, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stop_percent_step" class="form-label">{{ __('wallet-scenario.sell_stop_percent_step') }}</label>
                    <input type="number" step="any" name="sell_stop_percent_step" class="form-control form-control-lg" id="wallet-sell_stop_percent_step" value="@value($sell_stop_percent_step, 2)">
                </div>
            </div>

            <div class="xl:flex">
                <div class="flex-initial p-4">
                    <div class="form-check">
                        <input type="checkbox" name="sell_stop" value="1" class="form-check-switch" id="wallet-sell_stop" {{ $sell_stop ? 'checked' : '' }}>
                        <label for="wallet-sell_stop" class="form-check-label">{{ __('wallet-scenario.sell_stop') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box mt-5">
        <div class="px-5 py-3 border-b border-gray-200">
            <h2 class="font-medium text-base">
                {{ __('wallet-scenario.sell_stoploss_title') }}
            </h2>
        </div>

        <div class="p-3">
            <div class="xl:flex">
                <div class="flex-auto p-2">
                    <label for="wallet-sell_stoploss_percent_min" class="form-label">{{ __('wallet-scenario.sell_stoploss_percent_min') }}</label>
                    <input type="number" step="any" name="sell_stoploss_percent_min" class="form-control form-control-lg" id="wallet-sell_stoploss_percent_min" value="@value($sell_stoploss_percent_min, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stoploss_percent_max" class="form-label">{{ __('wallet-scenario.sell_stoploss_percent_max') }}</label>
                    <input type="number" step="any" name="sell_stoploss_percent_max" class="form-control form-control-lg" id="wallet-sell_stoploss_percent_max" value="@value($sell_stoploss_percent_max, 2)">
                </div>

                <div class="flex-auto p-2">
                    <label for="wallet-sell_stoploss_percent_step" class="form-label">{{ __('wallet-scenario.sell_stoploss_percent_step') }}</label>
                    <input type="number" step="any" name="sell_stoploss_percent_step" class="form-control form-control-lg" id="wallet-sell_stoploss_percent_step" value="@value($sell_stoploss_percent_step, 2)">
                </div>
            </div>

            <div class="xl:flex">
                <div class="flex-initial p-4">
                    <div class="form-check">
                        <input type="checkbox" name="sell_stoploss" value="1" class="form-check-switch" id="wallet-sell_stoploss" {{ $sell_stoploss ? 'checked' : '' }}>
                        <label for="wallet-sell_stoploss" class="form-check-label">{{ __('wallet-scenario.sell_stoploss') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="p-4">
            <div class="form-check">
                <input type="checkbox" name="exchange_reverse" value="1" class="form-check-switch" id="wallet-exchange_reverse" {{ $exchange_reverse ? 'checked' : '' }}>
                <label for="wallet-exchange_reverse" class="form-check-label">{{ __('wallet-scenario.exchange_reverse') }}</label>
            </div>
        </div>

        <div class="p-4">
            <div class="form-check">
                <input type="checkbox" name="exchange_first" value="1" class="form-check-switch" id="wallet-exchange_first" {{ $exchange_first ? 'checked' : '' }}>
                <label for="wallet-exchange_first" class="form-check-label">{{ __('wallet-scenario.exchange_first') }}</label>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('wallet-scenario.calculate') }}</button>
        </div>
    </div>
</form>

@if (isset($simulations))

<script>

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
    <input type="search" class="form-control form-control-lg" placeholder="{{ __('common.filter') }}" data-table-search="#wallet-scenario-table" />
</div>

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="wallet-scenario-table" class="table table-report text-center sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr>
                <th>{{ __('wallet-scenario.index') }}</th>
                <th>{{ __('wallet-scenario.buy_stop_min_percent') }}</th>
                <th>{{ __('wallet-scenario.buy_stop_max_percent') }}</th>
                <th>{{ __('wallet-scenario.buy_stop_count') }}</th>
                <th>{{ __('wallet-scenario.buy_stop_value') }}</th>
                <th>{{ __('wallet-scenario.sell_stop_max_percent') }}</th>
                <th>{{ __('wallet-scenario.sell_stop_min_percent') }}</th>
                <th>{{ __('wallet-scenario.sell_stop_count') }}</th>
                <th>{{ __('wallet-scenario.sell_stop_value') }}</th>
                <th>{{ __('wallet-scenario.sell_stoploss_percent') }}</th>
                <th>{{ __('wallet-scenario.sell_stoploss_count') }}</th>
                <th>{{ __('wallet-scenario.sell_stoploss_value') }}</th>
                <th>{{ __('wallet-scenario.start_value') }}</th>
                <th>{{ __('wallet-scenario.end_value') }}</th>
                <th>{{ __('wallet-scenario.profit') }}</th>
                <th>{{ __('wallet-scenario.simulator') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($simulations as $index => $each)

            <tr>
                <td>{{ $index }}</td>
                <td data-table-sort-value="{{ $each['buy_stop_min_percent'] }}">@number($each['buy_stop_min_percent'], 2)</td>
                <td data-table-sort-value="{{ $each['buy_stop_max_percent'] }}">@number($each['buy_stop_max_percent'], 2)</td>
                <td data-table-sort-value="{{ $each['buy_stop_count'] }}">{{ $each['buy_stop_count'] }}</td>
                <td data-table-sort-value="{{ $each['buy_stop_value'] }}">@number($each['buy_stop_value'], 2)</td>
                <td data-table-sort-value="{{ $each['sell_stop_max_percent'] }}">@number($each['sell_stop_max_percent'], 2)</td>
                <td data-table-sort-value="{{ $each['sell_stop_min_percent'] }}">@number($each['sell_stop_min_percent'], 2)</td>
                <td data-table-sort-value="{{ $each['sell_stop_count'] }}">{{ $each['sell_stop_count'] }}</td>
                <td data-table-sort-value="{{ $each['sell_stop_value'] }}">@number($each['sell_stop_value'], 2)</td>
                <td data-table-sort-value="{{ $each['sell_stoploss_percent'] }}">@number($each['sell_stoploss_percent'], 2)</td>
                <td data-table-sort-value="{{ $each['sell_stoploss_count'] }}">{{ $each['sell_stoploss_count'] }}</td>
                <td data-table-sort-value="{{ $each['sell_stoploss_value'] }}">@number($each['sell_stoploss_value'], 2)</td>
                <td data-table-sort-value="{{ $each['start_value'] }}">@number($each['start_value'], 2)</td>
                <td data-table-sort-value="{{ $each['end_value'] }}">@number($each['end_value'], 2)</td>
                <td data-table-sort-value="{{ $each['profit'] }}">@number($each['profit'], 2)</td>
                <td><a href="{{ $each['url'] }}" target="_blank">@icon('activity')</a></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endif

@endif

@stop
