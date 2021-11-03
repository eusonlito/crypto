@php ($dates = $exchanges->pluck('created_at'))

@if ($dates->first() < date('Y-m-d H:i:s', strtotime('-1 day')))
    @php ($dates = $dates->map(fn ($value) => strftime('%a %R', strtotime($value))))
@else
    @php ($dates = $dates->map(fn ($value) => strftime('%R', strtotime($value))))
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
                @if ($references && $row->buy_exchange)
                {
                    label: 'Buy Exchange',
                    yAxisID: 'y-axis-left',
                    backgroundColor: 'rgba(0, 0, 255, 0.4)',
                    borderColor: 'rgba(0, 0, 255, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges->count(), $row->buy_exchange))
                },
                @endif

                @if ($references && $row->sell_stop_min_value)
                {
                    label: 'Reference Value',
                    yAxisID: 'y-axis-right',
                    backgroundColor: 'rgba(0, 160, 0, 0.4)',
                    borderColor: 'rgba(0, 160, 0, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges->count(), $row->sell_stop_min_value))
                },
                @endif

                @if ($references && $row->buy_value)
                {
                    label: 'Buy Value',
                    yAxisID: 'y-axis-right',
                    backgroundColor: 'rgba(255, 160, 122, 0.4)',
                    borderColor: 'rgba(255, 160, 122, 0.4)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_fill(0, $exchanges->count(), $row->buy_value))
                },
                @endif

                {
                    label: 'Current Exchange',
                    yAxisID: 'y-axis-left',
                    backgroundColor: 'rgba(0, 0, 255, 0.7)',
                    borderColor: 'rgba(0, 0, 255, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($exchanges->pluck('exchange'))
                },
                {
                    display: false,
                    label: 'Wallet Value',
                    yAxisID: 'y-axis-right',
                    backgroundColor: 'rgba(255, 160, 122, 0.7)',
                    borderColor: 'rgba(255, 160, 122, 0.7)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 0,
                    data: @json($exchanges->map(static fn ($value) => $value->exchange * $row->amount))
                }
            ]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [
                    {
                        ticks: {
                            fontSize: '12',
                            fontColor: '#777777',
                            autoSkip: true
                        },
                        gridLines: {
                            display: false
                        },
                    },
                ],
                yAxes: [
                    {
                        id: 'y-axis-left',
                        ticks: {
                            fontSize: '12',
                            fontColor: '#777777',
                            callback: function(value) {
                                return value.toLocaleString('es-ES', {
                                    minimumFractionDigits: {{ $row->product->price_decimal }}
                                });
                            }
                        },
                        gridLines: {
                            color: '#D8D8D8',
                            zeroLineColor: '#D8D8D8',
                            borderDash: [2, 2],
                            zeroLineBorderDash: [2, 2],
                            drawBorder: false
                        },
                    },
                    {
                        id: 'y-axis-right',
                        position: 'right',
                        ticks: {
                            fontSize: '12',
                            fontColor: '#777777',
                            callback: function(value) {
                                return value.toLocaleString('es-ES', { minimumFractionDigits: 2 });
                            }
                        },
                        gridLines: {
                            display: false
                        }
                    }
                ]
            }
        }
    }
});
</script>

<canvas id="line-chart-{{ $row->id }}" height="150"></canvas>
