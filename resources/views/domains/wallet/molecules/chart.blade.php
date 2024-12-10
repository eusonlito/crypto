<script>
charts.push({
    id: 'line-chart-{{ $row->id }}',
    config: {
        type: 'line',
        elements: {
            line: {
                tension: 1
            }
        },
        data: {
            labels: @json($dates),

            datasets: [
                @if ($references && $row->buy_exchange)

                {
                    label: 'Buy Exchange',
                    yAxisID: 'yAxisLeft',
                    backgroundColor: 'rgba(0, 0, 255, 0.4)',
                    borderColor: 'rgba(0, 0, 255, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges_count, $row->buy_exchange)),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES', {
                                    minimumFractionDigits: {{ $row->product->price_decimal }}
                                });
                            }
                        }
                    }
                },

                @endif

                @if ($references && $row->sell_stop_min_value)

                {
                    label: 'Reference Value',
                    yAxisID: 'yAxisRight',
                    backgroundColor: 'rgba(0, 160, 0, 0.4)',
                    borderColor: 'rgba(0, 160, 0, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges_count, $row->sell_stop_min_value)),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES');
                            }
                        }
                    }
                },

                @endif

                @if ($references && $row->buy_value)

                {
                    label: 'Buy Value',
                    yAxisID: 'yAxisRight',
                    backgroundColor: 'rgba(255, 160, 122, 0.4)',
                    borderColor: 'rgba(255, 160, 122, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges_count, $row->buy_value)),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES');
                            }
                        }
                    }
                },

                @endif

                {
                    label: 'Current Exchange',
                    yAxisID: 'yAxisLeft',
                    backgroundColor: 'rgba(0, 0, 255, 0.7)',
                    borderColor: 'rgba(0, 0, 255, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_values($exchanges)),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const amount = {{ $row->amount }};
                                const exchange = context.raw;
                                const value = exchange * amount;
                                const difference = value - {{ $row->buy_value }};
                                const productDecimals = {{ $row->product->price_decimal }};

                                return context.dataset.label + ': ' + exchange.toLocaleString('es-ES', {
                                    minimumFractionDigits: productDecimals,
                                    maximumFractionDigits: productDecimals
                                }) + ' | ' + value.toLocaleString('es-ES', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) + ' | ' + difference.toLocaleString('es-ES', {
                                    signDisplay: 'exceptZero',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },

                {
                    display: false,
                    label: 'Wallet Value',
                    yAxisID: 'yAxisRight',
                    backgroundColor: 'rgba(255, 160, 122, 0.7)',
                    borderColor: 'rgba(255, 160, 122, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 0,
                    borderWidth: 0,
                    data: @json(array_map(fn ($value) => round($value * $row->amount, 2), array_values($exchanges))),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES');
                            }
                        }
                    }
                },

                @if ($orders->isNotEmpty())

                {
                    type: 'scatter',
                    label: 'Orders',

                    pointRadius: 5,

                    pointBackgroundColor: function (context) {
                        if (!context.raw) {
                            return;
                        }

                        if (context.raw.side === 'buy') {
                            return '#4F95E5';
                        }

                        if (context.raw.exchange >= context.raw.exchange_previous) {
                            return '#29A104';
                        }

                        return '#CB3737';
                    },

                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label
                                    + ' '
                                    + context.raw.side
                                    + ': '
                                    + context.raw.amount
                                    + ' * '
                                    + context.raw.exchange
                                    + ' = '
                                    + context.raw.value;
                            }
                        }
                    },

                    data: @json($orders),

                    parsing: {
                        xAxisKey: 'index',
                        yAxisKey: 'exchange',
                    },
                },

                @endif
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false
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
                yAxisLeft: {
                    position: 'left',
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
                },
                yAxisRight: {
                    position: 'right',
                    ticks: {
                        fontSize: '12',
                        fontColor: '#777777',
                        callback: function(value) {
                            return value.toLocaleString('es-ES', { minimumFractionDigits: 2 });
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    }
});
</script>

<canvas id="line-chart-{{ $row->id }}" height="150"></canvas>
