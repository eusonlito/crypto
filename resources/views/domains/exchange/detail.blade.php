@extends ('layouts.in')

@section ('body')

<script>var charts = new Array();</script>

<form method="get" class="mb-5">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <x-exchange-select name="time" :selected="$time" data-change-submit></x-exchange-select>
        </div>

        <div class="mt-2 sm:mt-0 bg-white">
            <a href="{{ $platform->url.$product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="btn form-select-lg">
                {{ $platform->name }}
            </a>
        </div>
    </div>
</form>

@if ($first && $last && $min && $max)

<div class="box whitespace-nowrap grid grid-cols-12 text-center p-1 sm:p-2 mb-5">
    <div class="col-span-6 md:col-span-3 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.first') }}</div>
        <div class="font-bold">@number($first)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.last') }}</div>
        <div class="font-bold">@number($last)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2 {{ ($last > $first) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.difference') }}</div>
        <div class="font-bold">@number($last - $first)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2 {{ ($last > $first) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.percent') }}</div>
        <div class="font-bold">@percent($first, $last)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.min') }}</div>
        <div class="font-bold">@number($min)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.max') }}</div>
        <div class="font-bold">@number($max)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2 {{ ($max > $min) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.difference') }}</div>
        <div class="font-bold">@number($max - $min)</div>
    </div>

    <div class="col-span-6 md:col-span-3 p-2 {{ ($max > $min) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.percent') }}</div>
        <div class="font-bold">@percent($min, $max)</div>
    </div>
</div>

@endif

@if ($list->isNotEmpty())

<div class="box p-5">
    @include ('domains.product.molecules.chart', [
        'row' => $product,
        'exchanges' => $list,
    ])
</div>

@endif

@if ($product->ask_price)

<div class="box whitespace-nowrap grid grid-cols-12 text-center p-1 sm:p-2 my-5">
    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.ask_price') }}</div>
        <div class="font-bold text-theme-24">@number($product->ask_price)</div>
    </div>

    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.ask_quantity') }}</div>
        <div class="font-bold">@number($product->ask_quantity, 2)</div>
    </div>

    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.ask_sum') }}</div>
        <div class="font-bold">@number($product->ask_sum, 2)</div>
    </div>

    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.bid_price') }}</div>
        <div class="font-bold text-theme-10">@number($product->bid_price)</div>
    </div>

    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.bid_quantity') }}</div>
        <div class="font-bold">@number($product->bid_quantity, 2)</div>
    </div>

    <div class="col-span-4 md:col-span-2 p-2">
        <div class="text-gray-600 text-xs">{{ __('exchange-detail.bid_sum') }}</div>
        <div class="font-bold">@number($product->bid_sum, 2)</div>
    </div>
</div>

@endif

@if ($list->isNotEmpty())

<div class="overflow-auto">
    <table class="table table-report text-center">
        <thead>
            <tr>
                <th>{{ __('exchange-detail.created_at') }}</th>
                <th>{{ __('exchange-detail.exchange') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list->reverse() as $row)

            <tr>
                <td title="{{ $row->created_at }}">@datetime($row->created_at)</td>
                <td title="{{ $row->exchange }}">@number($row->exchange)</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endif

@stop