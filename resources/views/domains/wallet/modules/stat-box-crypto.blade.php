@php ($prefix = 'stat-box-crypto-'.$row->id)

<div class="whitespace-nowrap" data-stat-box-crypto>
    <div class="box p-2">
        <div class="flex px-2 pb-2 items-center">
            <div class="percent-pill {{ ($current_exchange_percent < 0) ? 'bg-theme-24' : 'bg-theme-10' }}">
                @number($current_exchange_percent, 2)% @icon(($current_exchange_percent < 0) ? 'chevron-down' : 'chevron-up', 'w-4 h-4')
            </div>

            <div class="relative text-lg sm:text-xl font-bold ml-3 overflow-auto">
                <a href="{{ route('wallet.update', $row->id) }}">{{ $row->name }}</a>
                - <a href="{{ route('exchange.detail', $row->product_id) }}"class="text-gray-500">{{ $row->product->name }}</a>
                <a href="{{ $row->platform->url.$row->product->code }}" class="text-gray-400" rel="nofollow noopener noreferrer" target="_blank">- {{ $row->platform->name }}</a>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex flex-1 flex-wrap text-center">
                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">{{ __('wallet-stat.current_exchange') }}</div>
                    <div class="text-base" title="@numberString($current_exchange)" data-stat-box-crypto-current_exchange>@number($current_exchange)</div>

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.current_value') }}</div>
                    <div class="text-base" title="@numberString($current_value)" data-stat-box-crypto-current_value>@number($current_value)</div>

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.amount') }}</div>
                    <div class="text-base" title="@numberString($amount)" data-stat-box-crypto-amount>@number($amount)</div>
                </div>

                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">{{ __('wallet-stat.buy_exchange') }}</div>
                    <div class="text-base" title="@numberString($buy_exchange)" data-stat-box-crypto-buy_exchange>@number($buy_exchange)</div>

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.buy_value') }}</div>
                    <div class="text-base" title="@numberString($buy_value)" data-stat-box-crypto-buy_value>@number($buy_value)</div>

                    <div class="text-gray-600 text-xs mt-2">
                        <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-market-modal-{{ $row->id }}">
                            {{ __('wallet-stat.result') }}
                        </a>
                    </div>

                    <div class="text-base {{ ($result >= 0) ? 'text-theme-10' : 'text-theme-24' }} font-bold" data-stat-box-crypto-result>
                        <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-market-modal-{{ $row->id }}">
                            @number($result)
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex flex-1 flex-wrap text-center p-2 border-t lg:border-t-0 lg:border-l border-gray-300 border-dashed">
                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">
                        <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-buy-stop-modal-{{ $row->id }}">{{ __('wallet-stat.buy_stop') }}</a>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base {{ $buy_stop ? 'text-theme-10' : 'text-theme-24' }}">
                            <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-buy-stop-modal-{{ $row->id }}">
                                {{ $buy_stop ? __('wallet-stat.buy_stop_enabled') : __('wallet-stat.buy_stop_disabled') }}
                            </a>
                        </div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">
                        {{ __('wallet-stat.buy_stop_min_exchange') }}
                        @number($buy_stop_min_percent, 2)%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $buy_stop_min_at ? 'text-theme-10' : '' }}" title="{{ $buy_stop_min_at }}" data-stat-box-crypto-buy_stop_min_exchange>
                            @number($buy_stop_min_exchange)
                        </div>

                        <div class="{{ ($buy_stop_min_exchange_percent <= 0) ? 'text-theme-24' : 'text-theme-10' }} flex text-xs font-medium">
                            <span>@number($buy_stop_min_exchange_percent, 2)%</span>
                        </div>
                    </div>

                    @if ($buy_stop_min_value)

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.buy_stop_min_value') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $buy_stop_min_at ? 'text-theme-10' : '' }}" title="{{ $buy_stop_amount }}x{{ $buy_stop_min_exchange }}" data-stat-box-crypto-buy_stop_min_value>
                            @number($buy_stop_min_value)
                        </div>

                        <div class="{{ ($buy_stop_min_value_difference <= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span data-stat-box-crypto-buy_stop_min_value_difference>@number($buy_stop_min_value_difference, 2)</span>
                        </div>
                    </div>

                    @endif
                </div>

                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">{{ __('wallet-stat.buy_stop_amount') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base" title="@numberString($buy_stop_amount)" data-stat-box-crypto-buy_stop_amount>@number($buy_stop_amount)</div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">
                        {{ __('wallet-stat.buy_stop_max_exchange') }}
                        @number($buy_stop_max_percent, 2)%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $buy_stop_max_at ? 'text-theme-10' : '' }}" title="{{ $buy_stop_max_at }}" data-stat-box-crypto-buy_stop_max_exchange>
                            @number($buy_stop_max_exchange)
                        </div>

                        <div class="{{ ($buy_stop_max_exchange_percent <= 0) ? 'text-theme-24' : 'text-theme-10' }} flex text-xs font-medium">
                            <span>@number($buy_stop_max_exchange_percent, 2)%</span>
                        </div>
                    </div>

                    @if ($buy_stop_max_value)

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.buy_stop_max_value') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $buy_stop_max_at ? 'text-theme-10' : '' }}" title="{{ $buy_stop_amount }}x{{ $buy_stop_max_exchange }}" data-stat-box-crypto-buy_stop_max_value>
                            @number($buy_stop_max_value)
                        </div>

                        <div class="{{ ($buy_stop_max_value_difference <= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span data-stat-box-crypto-buy_stop_max_value_difference>@number($buy_stop_max_value_difference, 2)</span>
                        </div>
                    </div>

                    @endif
                </div>
            </div>

            <div class="flex flex-1 flex-wrap text-center p-2 border-t lg:border-t-0 lg:border-l border-gray-300 border-dashed">
                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">
                        <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-stop-modal-{{ $row->id }}">{{ __('wallet-stat.sell_stop') }}</a>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base {{ $sell_stop ? 'text-theme-10' : 'text-theme-24' }}">
                            <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-stop-modal-{{ $row->id }}">
                                {{ $sell_stop ? __('wallet-stat.sell_stop_enabled') : __('wallet-stat.sell_stop_disabled') }}
                            </a>
                        </div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">
                        {{ __('wallet-stat.sell_stop_max_exchange') }}
                        @number($sell_stop_max_percent, 2)%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stop_max_at ? 'text-theme-10' : '' }}" title="{{ $sell_stop_max_at }}" data-stat-box-crypto-sell_stop_max_exchange>
                            @number($sell_stop_max_exchange)
                        </div>

                        <div class="{{ ($sell_stop_max_exchange_percent >= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span>@number($sell_stop_max_exchange_percent, 2)%</span>
                        </div>
                    </div>

                    @if ($sell_stop_max_value)

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.sell_stop_max_value') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stop_max_at ? 'text-theme-10' : '' }}" title="{{ $sell_stop_amount }}x{{ $sell_stop_max_exchange }}" data-stat-box-crypto-sell_stop_max_value>
                            @number($sell_stop_max_value)
                        </div>

                        <div class="{{ ($sell_stop_max_value_difference >= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span data-stat-box-crypto-sell_stop_max_value_difference>@number($sell_stop_max_value_difference, 2)</span>
                        </div>
                    </div>

                    @endif
                </div>

                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">
                        {{ __('wallet-stat.sell_stop_amount') }}
                        {{ $sell_stop_percent }}%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base" title="@numberString($sell_stop_amount)" data-stat-box-crypto-sell_stop_amount>@number($sell_stop_amount)</div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">
                        {{ __('wallet-stat.sell_stop_min_exchange') }}
                        @number($sell_stop_min_percent, 2)%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stop_min_at ? 'text-theme-10' : '' }}" title="{{ $sell_stop_min_at }}" data-stat-box-crypto-sell_stop_min_exchange>
                            @number($sell_stop_min_exchange)
                        </div>

                        <div class="{{ ($sell_stop_min_exchange_percent >= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span>@number($sell_stop_min_exchange_percent, 2)%</span>
                        </div>
                    </div>

                    @if ($sell_stop_min_value)

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.sell_stop_min_value') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stop_min_at ? 'text-theme-10' : '' }}" title="{{ $sell_stop_amount }}x{{ $sell_stop_min_exchange }}" data-stat-box-crypto-sell_stop_min_value>
                            @number($sell_stop_min_value)
                        </div>

                        <div class="{{ ($sell_stop_min_value_difference >= 0) ? 'text-theme-10' : 'text-theme-24' }} flex text-xs font-medium">
                            <span data-stat-box-crypto-sell_stop_min_value_difference>@number($sell_stop_min_value_difference, 2)</span>
                        </div>
                    </div>

                    @endif
                </div>
            </div>

            <div class="flex flex-1 flex-wrap text-center p-2 border-t lg:border-t-0 lg:border-l border-gray-300 border-dashed">
                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">
                        <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-stoploss-modal-{{ $row->id }}">
                            {{ __('wallet-stat.sell_stoploss') }}
                        </a>
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base {{ $sell_stoploss ? 'text-theme-10' : 'text-theme-24' }}">
                            <a href="javascript:;" data-toggle="modal" data-target="#wallet-update-sell-stoploss-modal-{{ $row->id }}">
                                {{ $sell_stoploss ? __('wallet-stat.sell_stoploss_enabled') : __('wallet-stat.sell_stoploss_disabled') }}
                            </a>
                        </div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">
                        {{ __('wallet-stat.sell_stoploss_exchange') }}
                        @number($sell_stoploss_percent, 2)%
                    </div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stoploss_at ? 'text-theme-10' : '' }}" title="{{ $sell_stoploss_at }}" data-stat-box-crypto-sell_stoploss_exchange>
                            @number($sell_stoploss_exchange)
                        </div>

                        <div class="{{ ($sell_stoploss_exchange_percent < 0) ? 'text-theme-24' : 'text-theme-10' }} flex text-xs font-medium">
                            <span>@number($sell_stoploss_exchange_percent, 2)%</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-2">
                    <div class="text-gray-600 text-xs">{{ __('wallet-stat.sell_stoploss_amount') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base" data-stat-box-crypto-sell_stoploss_amount>@number($amount)</div>
                    </div>

                    <div class="text-gray-600 text-xs mt-2">{{ __('wallet-stat.sell_stoploss_value') }}</div>

                    <div class="flex items-center justify-center">
                        <div class="text-base mr-2 {{ $sell_stoploss_at ? 'text-theme-10' : '' }}" title="{{ $amount }}x{{ $sell_stoploss_exchange }}" data-stat-box-crypto-sell_stoploss_value>
                            @number($sell_stoploss_value)
                        </div>

                        <div class="text-theme-24 flex text-xs font-medium">
                            <span data-stat-box-crypto-sell_stoploss_value_difference>@number($sell_stoploss_value - $buy_value, 2)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include ('domains.wallet.molecules.wallet-update-sell-market-modal')
@include ('domains.wallet.molecules.wallet-update-buy-stop-modal')
@include ('domains.wallet.molecules.wallet-update-sell-stop-modal')
@include ('domains.wallet.molecules.wallet-update-sell-stoploss-modal')
