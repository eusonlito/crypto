@php ($filled ??= false)
@php ($paginated = method_exists($list, 'hasPages'))

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="order-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort {{ $paginated ? '' : 'data-table-pagination' }}>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('order-index.date') }}</th>
                <th class="text-center">{{ __('order-index.wallet') }}</th>
                <th class="text-center">{{ __('order-index.platform') }}</th>
                <th class="text-center">{{ __('order-index.side') }}</th>
                <th>{{ __('order-index.amount') }}</th>
                <th>{{ __('order-index.price') }}</th>

                @if ($filled)
                <th>{{ __('order-index.exchange_current') }}</th>
                @endif

                <th>{{ __('order-index.value') }}</th>

                @if ($filled)
                <th>{{ __('order-index.value_current') }}</th>
                @endif

                <th>{{ __('order-index.difference') }}</th>

                <th class="text-center">{{ __('order-index.type') }}</th>
                <th class="text-center">{{ __('order-index.status') }}</th>
                <th class="text-center">{{ __('order-index.filled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td>
                    @if ($row->custom)

                    <a href="{{ route('order.update', $row->id) }}" class="block text-center whitespace-nowrap" title="{{ $row->updated_at }}">@datetime($row->updated_at)</a>

                    @else

                    <span class="block text-center whitespace-nowrap" title="{{ $row->updated_at }}">@datetime($row->updated_at)</span>

                    @endif
                </td>

                <td>
                    @if ($row->wallet_id)

                    <a href="{{ route('wallet.update', $row->wallet_id) }}" class="block text-center font-semibold whitespace-nowrap external">{{ $row->product->acronym }}</a>

                    @else

                    <span class="block text-center font-semibold whitespace-nowrap external">{{ $row->product->acronym }}</span>

                    @endif
                </td>

                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-center font-semibold whitespace-nowrap external">{{ $row->platform->name }}</a></td>
                <td><span class="block text-center">{{ ($row->side === 'sell') ? __('order-index.sell') : __('order-index.buy') }}</span></td>
                <td data-table-sort-value="{{ $row->amount }}" data-copy-value="{{ $row->amount }}"><span class="block" title="{{ $row->amount }}">@number($row->amount)</span></td>
                <td data-table-sort-value="{{ $row->price }}" data-copy-value="{{ $row->price }}"><span class="block" title="{{ $row->price }}">@number($row->price)</span></td>

                @if ($filled)

                <td data-table-sort-value="{{ $row->exchange_current }}" data-copy-value="{{ $row->exchange_current }}"><span class="block">@number($row->exchange_current)</span></td>

                @endif

                <td data-table-sort-value="{{ $row->value }}" data-copy-value="{{ $row->value }}"><span class="block" title="{{ $row->value }}">@number($row->value)</span></td>

                @if ($filled)

                <td><span class="block {{ $row->success ? 'text-theme-10' : 'text-theme-24' }}" title="@number($row->difference)">@number($row->value_current)</span></td>

                @endif

                <td>
                    <span title="{{ $row->value - $row->previous_value }}">@number($row->value - $row->previous_value, 2)</span>
                    <span class="ml-2 text-xs font-medium {{ (($row->side === 'sell') && ($row->previous_percent < 0)) ? 'text-theme-24' : 'text-theme-10' }}">
                        @number($row->previous_percent, 2)%
                    </span>
                </td>

                <td><span class="block text-center">{{ $row->type }}</span></td>
                <td><span class="block text-center">{{ $row->status }}</span></td>
                <td><span class="block text-center">@status($row->filled)</span></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@if ($paginated && $list->hasPages())

<div class="mt-2">
    {{ $list->appends($REQUEST->input())->links() }}
</div>

@endif
