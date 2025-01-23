@extends ('layouts.in')

@section ('body')

<script>var charts = new Array();</script>

<x-wallet-stat-global :investment="$investment" :list="$walletsValues" />

@if ($walletsFiat->isNotEmpty())

<x-wallet-stat-box-fiat :list="$walletsFiat" />

@endif

@if (($wallets = $walletsCrypto->where('sell_stop', true))->isNotEmpty())

<h2 class="box py-3 px-5 mt-5 text-lg font-medium">
    {{ __('dashboard-index.selling') }}
</h2>

@foreach ($wallets as $row)

<div class="mt-4">
    <x-wallet-stat-box-crypto :row="$row" />
</div>

@endforeach

@endif

@if (($wallets = $walletsCrypto->where('buy_stop', true))->isNotEmpty())

<h2 class="box py-3 px-5 mt-5 text-lg font-medium">
    {{ __('dashboard-index.buying') }}
</h2>

@foreach ($wallets as $row)

<div class="mt-4">
    <x-wallet-stat-box-crypto :row="$row" />
</div>

@endforeach

@endif

<div class="mt-4 lg:grid grid-flow-col gap-4">
    @foreach ($tickers as $row)

    <x-ticker-stat-box :row="$row" />

    @endforeach
</div>

<form action="#walletsCharts" method="get" id="walletsCharts" class="mt-4">
    <div class="flex space-x-4">
        <div class="flex-grow">
            <x-exchange-select name="time" :selected="$filters['time']" data-change-submit></x-exchange-select>
        </div>

        <div class="ml-4">
            <select name="references" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="1" {{ ($filters['references'] === true) ? 'selected' : '' }}>{{ __('dashboard-index.with-references') }}</option>
                <option value="0" {{ ($filters['references'] === false) ? 'selected' : '' }}>{{ __('dashboard-index.without-references') }}</option>
            </select>
        </div>
    </div>
</form>

<div class="grid grid-cols-12 gap-4 mt-4">
    @foreach ($walletsCrypto->where('sell_stop', true) as $row)

    <x-wallet-chart :row="$row" :references="$filters['references']" />

    @endforeach

    @foreach ($walletsCrypto->where('buy_stop', true) as $row)

    <x-wallet-chart :row="$row" :references="$filters['references']" />

    @endforeach

    @foreach ($tickers->whereNotIn('product_id', $walletsCrypto->pluck('product_id')) as $row)

    <x-ticker-chart :row="$row" :references="$filters['references']" />

    @endforeach
</div>

@if ($ordersFilled->isNotEmpty())

<h2 class="box py-3 px-5 mt-5 text-lg font-medium">
    {{ __('dashboard-index.orders-filled') }}
</h2>

@include ('domains.order.molecules.list', ['list' => $ordersFilled])

@endif

@if ($ordersOpen->isNotEmpty())

<h2 class="box py-3 px-5 mt-5 text-lg font-medium">
    {{ $ordersOpen->count() }}
    {{ __('dashboard-index.orders-open') }}
</h2>

@include ('domains.order.molecules.list', [
    'list' => $ordersOpen,
    'difference' => false,
])

@endif

@stop
