<div class="box col-span-12 lg:col-span-4" data-grid-12>
    <div class="p-3" id="line-chart">
        <h2 class="font-medium text-center mb-2 overflow-auto">
            <a href="{{ route('exchange.detail', $row->product_id) }}">{{ $row->product->title().' - '.$row->platform->name  }}</a>
        </h2>

        @include ('domains.ticker.molecules.chart')
    </div>
</div>
