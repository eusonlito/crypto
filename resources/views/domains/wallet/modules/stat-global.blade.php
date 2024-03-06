<div class="box whitespace-nowrap flex flex-wrap text-center p-1 sm:p-2">
    <div class="flex-1 p-2">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.current_value') }}</div>
        <div class="font-bold">@number($current_value)</div>
    </div>

    <div class="flex-1 p-2">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.buy_value') }}</div>
        <div class="font-bold"><a href="{{ route('user.update') }}">@number($buy_value)</a></div>
    </div>

    <div class="flex-1 p-2 {{ ($sell_stop_min_value && ($current_value >= $sell_stop_min_value)) ? 'text-theme-10' : '' }}">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.sell_stop_min_value') }}</div>
        <div class="font-bold">@number($sell_stop_min_value)</div>
    </div>

    <div class="flex-1 p-2">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.investment') }}</div>
        <div class="font-bold"><a href="{{ route('user.update') }}">@number($investment)</a></div>
    </div>

    <div class="flex-1 p-2 {{ ($result >= 0) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.result') }}</div>
        <div class="font-bold">@number($result)</div>
    </div>

    <div class="flex-1 p-2 {{ ($result >= 0) ? 'text-theme-10' : 'text-theme-24' }}">
        <div class="text-gray-600 text-xs">{{ __('wallet-stat-global.difference') }}</div>
        <div class="font-bold">@percent($investment, $current_value)%</div>
    </div>
</div>
