<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="order-status-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('order-status.product') }}</th>
                <th class="text-center">{{ __('order-status.platform') }}</th>
                <th class="text-center">{{ __('order-status.investment') }}</th>
                <th class="text-center">{{ __('order-status.buy-operations') }}</th>
                <th class="text-center">{{ __('order-status.sell-operations') }}</th>
                <th class="text-center" title="{{ __('order-status.buy-price-average-description') }}">{{ __('order-status.buy-price-average') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-price-average-description') }}">{{ __('order-status.sell-price-average') }}</th>
                <th class="text-center" title="{{ __('order-status.buy-value-description') }}">{{ __('order-status.buy-value') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-value-description') }}">{{ __('order-status.sell-value') }}</th>
                <th class="text-center" title="{{ __('order-status.buy-amount-description') }}">{{ __('order-status.buy-amount') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-amount-description') }}">{{ __('order-status.sell-amount') }}</th>
                <th class="text-center" title="{{ __('order-status.wallet-amount-description') }}">{{ __('order-status.wallet-amount') }}</th>
                <th class="text-center" title="{{ __('order-status.wallet-value-description') }}">{{ __('order-status.wallet-value') }}</th>
                <th class="text-center">{{ __('order-status.balance') }}</th>
                <th class="text-center" title="{{ __('order-status.balance-percent-description') }}">{{ __('order-status.balance-percent') }}</th>
                <th class="text-center" title="{{ __('order-status.balance-percent-daily-description') }}">{{ __('order-status.balance-percent-daily') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><a href="{{ route('wallet.update', $row->wallet->id) }}" class="block text-center font-semibold whitespace-nowrap external">{{ $row->product->acronym }}</a></td>

                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-center font-semibold whitespace-nowrap external">{{ $row->platform->name }}</a></td>

                <td><span class="block">@number($row->investment, 2)</span></td>

                <td class="text-center" title="@datetime($row->date_first) - @datetime($row->date_last)"><span class="block">@number($row->buy_count, 0)</span></td>
                <td class="text-center" title="@datetime($row->date_first) - @datetime($row->date_last)"><span class="block">@number($row->sell_count, 0)</span></td>

                <td><span class="block">@number($row->buy_average)</span></td>
                <td><span class="block">@number($row->sell_average)</span></td>

                <td><span class="block" title="{{ Html::orderBuySellTitle($row->buy) }}">@number($row->buy_value, 2)</span></td>
                <td><span class="block" title="{{ Html::orderBuySellTitle($row->sell) }}">@number($row->sell_value, 2)</span></td>

                <td><span class="block">@number($row->buy_amount)</span></td>
                <td><span class="block">@number($row->sell_amount)</span></td>

                <td><span class="block">@number($row->wallet_amount)</span></td>
                <td><span class="block">@number($row->wallet_value, 2)</span></td>

                <td><span class="block">@number($row->balance, 2)</span></td>
                <td><span class="block">@number($row->balance_percent, 2)%</span></td>
                <td><span class="block">@number($row->balance_percent_daily, 2)%</span></td>
            </tr>

            @endforeach

            <tr class="text-right">
                <td colspan="2"></td>
                <td class="text-center"><span class="block">@number($total['investment'], 2)</span></td>
                <td class="text-center"><span class="block">@number($total['buy_count'], 0)</span></td>
                <td class="text-center"><span class="block">@number($total['sell_count'], 0)</span></td>
                <td colspan="2"></td>
                <td><span class="block">@number($total['buy_value'], 2)</span></td>
                <td><span class="block">@number($total['sell_value'], 2)</span></td>
                <td colspan="3"></td>
                <td><span class="block">@number($total['wallet_value'], 2)</span></td>
                <td><span class="block">@number($total['balance'], 2)</span></td>
                <td><span class="block">@number($total['balance_percent'], 2)%</span></td>
                <td><span class="block">@number($total['balance_percent_daily'], 2)%</span></td>
            </tr>
        </tbody>
    </table>
</div>
