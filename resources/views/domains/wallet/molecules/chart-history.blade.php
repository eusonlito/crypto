@php ($dates = $history->pluck('created_at'))

<canvas id="wallet-history-chart-canvas" height="500"></canvas>

<script>
const charts = [{
    id: 'wallet-history-chart-canvas',

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
                    label: 'Value',
                    yAxisID: 'yAxisLeft',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 2,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($history->pluck('payload.current_value')),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString('es-ES', {
                                    minimumFractionDigits: {{ $row->product->price_decimal }}
                                });
                            }
                        }
                    }
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
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
                }
            }
        }
    }
}];
</script>
