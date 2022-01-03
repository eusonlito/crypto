@php ($dates = $exchanges->pluck('created_at'))

<canvas id="product-chart-canvas" height="105"></canvas>

<script>
const productChart = {
    id: 'product-chart-canvas',
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
                    yAxisID: 'yAxisLeft',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 2,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($exchanges->pluck('exchange')),
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.raw.toLocaleString('es-ES', {
                                    minimumFractionDigits: {{ $row->price_decimal }}
                                });
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
                }
            },
            scales: {
                x: {
                    ticks: {
                        fontSize: '12',
                        fontColor: '#777777',
                        autoSkip: true,
                        minRotation: 90,
                        maxRotation: 90,
                        callback: function(value) {
                            const date = new Date(Date.parse(this.getLabelForValue(value)));

                            return date.getDate()
                                + ' ' + ('0' + date.getHours()).slice(-2)
                                + ':' + ('0' + date.getMinutes()).slice(-2);
                        }
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
                                minimumFractionDigits: {{ $row->price_decimal }}
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
};

</script>
