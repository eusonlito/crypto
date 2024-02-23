@php ($dates = $exchanges->pluck('created_at'))

@if ($dates->first() < date('Y-m-d H:i:s', strtotime('-1 day')))
    @php ($dates = $dates->map(fn ($value) => date('D H:i', strtotime($value))))
@else
    @php ($dates = $dates->map(fn ($value) => date('H:i', strtotime($value))))
@endif

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
                @if ($references && $row->exchange_reference)

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
                    data: @json(array_fill(0, $exchanges->count(), $row->exchange_reference)),
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

                @if ($references && $row->value_reference)

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
                    data: @json(array_fill(0, $exchanges->count(), $row->value_reference)),
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
                    data: @json($exchanges->pluck('exchange')),
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
                {
                    display: false,
                    label: 'Wallet Value',
                    yAxisID: 'yAxisRight',
                    backgroundColor: 'rgba(255, 160, 122, 0.7)',
                    borderColor: 'rgba(255, 160, 122, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 0,
                    data: @json($exchanges->map(static fn ($value) => $value->exchange * $row->amount)),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES');
                            }
                        }
                    }
                }
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
