@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('exchange-variance.search') }}" data-table-search="#exchange-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="currency_quote_id" value="currency_quote_id" text="name" :options="$products->toArray()" :selected="$filters['currency_quote_id']" :placeholder="__('exchange-variance.products-all')" data-change-submit></x-select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('exchange-variance.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="exchange-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-left">{{ __('exchange-index.code') }}</th>
                <th class="text-left">{{ __('exchange-index.name') }}</th>

                @foreach (array_keys($dates) as $code)

                <th class="text-center">{{ $code }}</th>

                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $i => $product)

            @php ($link = route('exchange.detail', $product->id))

            <tr>
                <td class="text-center"><a href="{{ $link }}" class="block">{{ $i + 1 }}</a></td>
                <td class="text-left"><a href="{{ $link }}" class="block">{{ $product->acronym }}</a></td>
                <td class="text-left"><a href="{{ $link }}" class="block whitespace-nowrap">{{ $product->name }}</a></td>

                @foreach (array_keys($dates) as $code)

                @php ($percent = $product->percents[$code] ?? 0)
                @php ($value = $product->values[$code] ?? 0)

                <td class="{{ ($percent >= 0) ? 'text-theme-10' : 'text-theme-24' }} text-xs font-medium text-center" data-table-sort-value="{{ $percent }}">
                    <a href="{{ $link }}" class="block">
                        <div>@number($value)</div>
                        <div>{{ $percent }}%</div>
                    </a>
                </td>

                @endforeach
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@stop
