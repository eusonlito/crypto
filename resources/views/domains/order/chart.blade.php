@extends ('layouts.in')

@section ('body')

<form method="get">
    <button type="submit" class="hidden"></button>

    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" name="search" class="form-control form-control-lg" placeholder="{{ __('order-status.search') }}" data-table-search="#order-status-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <input type="text" name="date_start" class="form-control form-control-lg" value="{{ $filters['date_start'] }}" placeholder="{{ __('order-index.date_start') }}" data-datepicker data-change-submit />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <input type="text" name="date_end" class="form-control form-control-lg" value="{{ $filters['date_end'] }}" placeholder="{{ __('order-index.date_end') }}" data-datepicker data-change-submit />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('order-status.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

<script>

const color = function(str) {
    let hash = 5381;

    for (let i = 0; i < str.length; i++) {
        hash = ((hash << 5) + hash) + str.charCodeAt(i);
    }

    const hue = (Math.abs(hash) % 360 * 7 + 177) % 360;

    return `hsl(${hue}, 100%, 50%)`;
}

var charts = new Array();

charts.push({
    id: 'order-chart',
    config: {
        type: 'bar',
        data: {
            labels: @json($labels),

            datasets: [
                @foreach ($values as $name => $value)

                {
                    label: '{{ $name }}',
                    yAxisID: 'yAxisLeft',
                    backgroundColor: color('{{ $name }}'),
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json($value)
                },

                @endforeach
            ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: {
                        autoSkip: true
                    },
                    grid: {
                        display: false
                    },
                },
                yAxisLeft: {
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('es-ES', {
                                minimumFractionDigits: 2
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

<div class="box p-5 mt-5 h-3/4">
    <canvas id="order-chart"></canvas>
</div>

@stop
