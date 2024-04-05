<div class="whitespace-nowrap mt-4 lg:mt-0 box p-2">
    <div class="flex px-2 pb-2 items-center">
        <div class="percent-pill {{ ($result_current < 0) ? 'bg-theme-24' : 'bg-theme-10' }}">
            <a href="{{ route('ticker.update', $row->id) }}">{{ $row->name }}
                @number($value_current_percent, 2)% @icon(($result_current < 0) ? 'chevron-down' : 'chevron-up', 'w-4 h-4')
            </a>
        </div>

        <div class="relative text-lg sm:text-xl font-bold ml-3 overflow-auto">
            <a href="{{ route('exchange.detail', $row->product_id) }}"class="text-gray-500">{{ $row->product->name }}</a>
            <a href="{{ $row->platform->url.$row->product->code }}" class="text-gray-400" rel="nofollow noopener noreferrer" target="_blank">- {{ $row->platform->name }}</a>
        </div>
    </div>

    <div class="flex flex-wrap text-center">
        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.exchange_current') }}</div>
            <div class="text-base" title="@numberString($exchange_current)">@number($exchange_current)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.value_current') }}</div>
            <div class="text-base" title="@numberString($value_current)">@number($value_current)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.result_current') }}</div>
            <div class="text-base {{ ($result_current >= 0) ? 'text-theme-10' : 'text-theme-24' }} font-bold" title="@numberString($result_current)">@number($result_current)</div>
        </div>
    </div>

    <div class="flex flex-wrap text-center">
        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.exchange_reference') }}</div>
            <div class="text-base" title="{{ $row->date_at }}">@number($exchange_reference)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.value_reference') }}</div>
            <div class="text-base" title="@numberString($value_reference)">@number($value_reference)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.amount') }}</div>
            <div class="text-base" title="@numberString($amount)">@number($amount)</div>
        </div>
    </div>

    <div class="flex flex-wrap text-center">
        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.exchange_min') }}</div>
            <div class="text-base" title="{{ $exchange_min_at }}">@number($exchange_min)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.value_min') }}</div>
            <div class="text-base" title="@numberString($value_min)">@number($value_min)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.result_min') }}</div>
            <div class="text-base {{ ($result_min >= 0) ? 'text-theme-10' : 'text-theme-24' }} font-bold" title="@numberString($result_min)">@number($result_min)</div>
        </div>
    </div>

    <div class="flex flex-wrap text-center">
        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.exchange_max') }}</div>
            <div class="text-base" title="{{ $exchange_max_at }}">@number($exchange_max)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.value_max') }}</div>
            <div class="text-base" title="@numberString($value_max)">@number($value_max)</div>
        </div>

        <div class="flex-1 p-2">
            <div class="text-gray-600 text-xs">{{ __('ticker-stat.result_max') }}</div>
            <div class="text-base {{ ($result_max >= 0) ? 'text-theme-10' : 'text-theme-24' }} font-bold" title="@numberString($result_max)">@number($result_max)</div>
        </div>
    </div>
</div>
