@php ($dates = $exchanges->pluck('created_at'))

@if ($dates->first() < date('Y-m-d H:i:s', strtotime('-1 day')))
    @php ($dates = $dates->map(fn ($value) => strftime('%a %R', strtotime($value))))
@else
    @php ($dates = $dates->map(fn ($value) => strftime('%R', strtotime($value))))
@endif

<script>
charts.push({
    id: 'line-chart-{{ $row->code }}',
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
                {
                    label: 'Current Exchange',
                    yAxisID: 'y-axis-left',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 2,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($exchanges->pluck('exchange'))
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
                                    minimumFractionDigits: {{ $row->price_decimal }}
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
                    }
                ]
            }
        }
    }
});
</script>

<canvas id="line-chart-{{ $row->code }}" height="105"></canvas>
