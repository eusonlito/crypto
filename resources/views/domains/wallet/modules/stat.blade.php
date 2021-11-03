<div class="box p-5 mt-2 border-b border-gray-200">
    <div class="md:flex flex-col sm:flex-row items-center">
        <h2 class="font-medium text-base mr-auto">
            {{ $row->name }}
        </h2>

        <div class="md:flex text-gray-600 font-bold text-right text-base">
            <div>
                <div>@number($current_exchange)</div>
                <div class="text-xs">{{ __('wallet-stat.current_exchange') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div>
                <div>@number($buy_exchange)</div>
                <div class="text-xs">{{ __('wallet-stat.buy_exchange') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div>
                <div>@number($sell_stop_min)</div>
                <div class="text-xs">{{ __('wallet-stat.sell_stop_min') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div>
                <div>@number($amount)</div>
                <div class="text-xs">{{ __('wallet-stat.amount') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div class="{{ ($sell_stop_min_value && ($current_value >= $sell_stop_min_value)) ? 'text-theme-10' : '' }}">
                <div>@number($sell_stop_min_value)</div>
                <div class="text-xs">{{ __('wallet-stat.sell_stop_min_value') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div class="text-theme-19">
                <div>@number($buy_value)</div>
                <div class="text-xs">{{ __('wallet-stat.buy_value') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div class="text-theme-19">
                <div>@number($current_value)</div>
                <div class="text-xs">{{ __('wallet-stat.current_value') }}</div>
            </div>

            <div class="w-px h-12 border border-r border-dashed border-gray-300 mx-4 xl:mx-6 invisible md:visible"></div>

            <div class="{{ ($result >= 0) ? 'text-theme-10' : 'text-theme-24' }}">
                <div>@number($result)</div>
                <div class="text-xs">{{ __('wallet-stat.result') }}</div>
            </div>
        </div>
    </div>
</div>