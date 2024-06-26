<div class="overflow-auto header-sticky">
    <table id="wallet-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('wallet-index.name') }}</th>
                <th class="text-center">{{ __('wallet-index.product') }}</th>
                <th class="text-center">{{ __('wallet-index.platform') }}</th>
                <th>{{ __('wallet-index.amount') }}</th>
                <th>{{ __('wallet-index.buy_exchange') }}</th>
                <th>{{ __('wallet-index.current_exchange') }}</th>
                <th>{{ __('wallet-index.buy_value') }}</th>
                <th>{{ __('wallet-index.current_value') }}</th>
                <th class="text-center">{{ __('wallet-index.buy_stop') }}</th>
                <th class="text-center">{{ __('wallet-index.sell_stop') }}</th>
                <th class="text-center">{{ __('wallet-index.sell_stoploss') }}</th>
                <th class="text-center">{{ __('wallet-index.custom') }}</th>
                <th class="text-center">{{ __('wallet-index.order') }}</th>
                <th class="text-center">{{ __('wallet-index.visible') }}</th>
                <th class="text-center">{{ __('wallet-index.enabled') }}</th>
                <th class="text-center">{{ __('wallet-index.processing_at') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            @php ($link = route('wallet.update', $row->id))

            <tr class="text-right">
                <td><a href="{{ $link }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->name }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap external">{{ $row->product->acronym }}</a></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-center font-semibold whitespace-nowrap external">{{ $row->platform->name }}</a></td>
                <td data-table-sort-value="{{ $row->amount }}" data-copy-value="{{ $row->amount }}"><a href="{{ $link }}" class="block" title="@numberString($row->amount)">@number($row->amount)</a></td>
                <td data-table-sort-value="{{ $row->buy_exchange }}" data-copy-value="{{ $row->buy_exchange }}"><a href="{{ $link }}" class="block" title="@numberString($row->buy_exchange)">@number($row->buy_exchange)</a></td>
                <td data-table-sort-value="{{ $row->current_exchange }}" data-copy-value="{{ $row->current_exchange }}"><a href="{{ $link }}" class="block" title="@number($row->current_exchange - $row->buy_exchange)">@number($row->current_exchange)</a></td>
                <td data-table-sort-value="{{ $row->buy_value }}" data-copy-value="{{ $row->buy_value }}"><a href="{{ $link }}" class="block" title="@numberString($row->buy_value)">@number($row->buy_value)</a></td>
                <td data-table-sort-value="{{ $row->current_value }}" data-copy-value="{{ $row->current_value }}"><a href="{{ $link }}" class="block {{ ($row->current_value >= $row->buy_value) ? 'text-theme-10' : 'text-theme-24' }}" title="@number($row->current_value - $row->buy_value)">@number($row->current_value)</a></td>
                <td><a href="{{ route('wallet.update.boolean', [$row->id, 'buy_stop']) }}" data-link-boolean="buy_stop" class="block text-center">@status($row->buy_stop)</a></td>
                <td><a href="{{ route('wallet.update.boolean', [$row->id, 'sell_stop']) }}" data-link-boolean="sell_stop" class="block text-center">@status($row->sell_stop)</a></td>
                <td><a href="{{ $link }}" class="block text-center">@status($row->sell_stoploss)</a></td>
                <td><a href="{{ $link }}" class="block text-center">@status($row->custom)</a></td>
                <td class="text-center"><input type="text" name="value" data-link-form="{{ route('wallet.update.column', [$row->id, 'order']) }}" value="{{ $row->order }}" class="w-10 px-2 border text-center" /></td>
                <td><a href="{{ route('wallet.update.boolean', [$row->id, 'visible']) }}" data-link-boolean="visible" class="block text-center">@status($row->visible)</a></td>
                <td><a href="{{ route('wallet.update.boolean', [$row->id, 'enabled']) }}" data-link-boolean="enabled" class="block text-center">@status($row->enabled)</a></td>
                <td><a href="{{ route('wallet.update.boolean', [$row->id, 'processing_at']) }}" data-link-boolean="processing_at" class="block text-center" title="{{ $row->processing_at }}">@status(boolval($row->processing_at))</a></td>
            </tr>

            @endforeach
        </tbody>

        <tfoot>
            <tr class="text-right">
                <td colspan="6"></td>
                <td>@number($list->sum('buy_value'))</td>
                <td>@number($list->sum('current_value'))</td>
                <td colspan="8"></td>
            </tr>
        </tfoot>
    </table>
</div>
