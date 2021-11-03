<div id="order-status-table" class="overflow-auto">
    <table class="table table-report sm:mt-2">
        <thead>
            <tr class="text-right">
                <th class="text-left">{{ __('order-status.platform') }}</th>
                <th class="text-center">{{ __('order-status.product') }}</th>
                <th class="text-center">{{ __('order-status.wallet') }}</th>
                <th class="text-center">{{ __('order-status.buy-operations') }}</th>
                <th class="text-center">{{ __('order-status.sell-operations') }}</th>
                <th class="text-center">{{ __('order-status.date-first') }}</th>
                <th class="text-center">{{ __('order-status.date-last') }}</th>
                <th class="text-center" title="{{ __('order-status.buy-price-average-description') }}">{{ __('order-status.buy-price-average') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-price-average-description') }}">{{ __('order-status.sell-price-average') }}</th>
                <th class="text-center" title="{{ __('order-status.buy-value-description') }}">{{ __('order-status.buy-value') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-value-description') }}">{{ __('order-status.sell-value') }}</th>
                <th class="text-center" title="{{ __('order-status.wallet-price-value-description') }}">{{ __('order-status.wallet-price-value') }}</th>
                <th class="text-center" title="{{ __('order-status.sell-pending-average-description') }}">{{ __('order-status.sell-pending-average') }}</th>
                <th class="text-center">{{ __('order-status.balance') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-left font-semibold whitespace-nowrap">{{ $row->platform->name }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->product->acronym }}</a></td>
                <td class="text-center">
                    @if ($row->wallet)
                    <a href="{{ route('wallet.update', $row->wallet->id) }}" class="block font-semibold whitespace-nowrap">{{ $row->wallet->name }}</a>
                    @else
                    -
                    @endif
                </td>

                <td class="text-center"><span class="block">@number($row->buy_count, 0)</span></td>
                <td class="text-center"><span class="block">@number($row->sell_count, 0)</span></td>

                <td><span class="block">@datetime($row->date_first)</span></td>
                <td><span class="block">@datetime($row->date_last)</span></td>

                <td><span class="block">@number($row->buy_average)</span></td>
                <td><span class="block">@number($row->sell_average)</span></td>

                <td><span class="block" title="{{ Html::orderBuySellTitle($row->buy) }}">@number($row->buy_value)</span></td>
                <td><span class="block" title="{{ Html::orderBuySellTitle($row->sell) }}">@number($row->sell_value)</span></td>

                @if ($row->wallet)
                <td><span class="block">@number($row->wallet->amount)</span></td>
                @else
                <td>-</td>
                @endif

                <td><span class="block" title="{{ Html::orderSellPendingTitle($row->sell_pending) }}">@number($row->sell_pending_average)</span></td>
                <td><span class="block">@number($row->balance, 2)</span></td>
            </tr>

            @endforeach
        </tbody>

        <tfoot>
            <tr class="text-right">
                <th colspan="13">{{ __('order-status.total') }}</th>
                <th><span class="block">@number($list->sum('balance'))</span></th>
            </tr>
        </tfoot>
    </table>
</div>