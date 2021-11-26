@php ($filled ??= false)

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="order-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('order-index.date') }}</th>
                <th class="text-left">{{ __('order-index.platform') }}</th>
                <th class="text-center">{{ __('order-index.product') }}</th>
                <th>{{ __('order-index.amount') }}</th>
                <th>{{ __('order-index.price') }}</th>

                @if ($filled)
                <th>{{ __('order-index.exchange_current') }}</th>
                @endif

                <th>{{ __('order-index.value') }}</th>

                @if ($filled)
                <th>{{ __('order-index.value_current') }}</th>
                @endif

                <th>{{ __('order-index.fee') }}</th>
                <th class="text-center">{{ __('order-index.type') }}</th>
                <th class="text-center">{{ __('order-index.status') }}</th>
                <th class="text-center">{{ __('order-index.side') }}</th>
                <th class="text-center">{{ __('order-index.filled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><span class="block text-center" title="{{ $row->created_at }}">@datetime($row->created_at)</span></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-left font-semibold whitespace-nowrap">{{ $row->platform->name }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->product->acronym }}</a></td>
                <td><span class="block" title="{{ $row->amount }}">@number($row->amount)</span></td>
                <td><span class="block" title="{{ $row->price }}">@number($row->price)</span></td>

                @if ($filled)

                <td><span class="block">@number($row->exchange_current)</span></td>

                @endif

                <td><span class="block" title="{{ $row->value }}">@number($row->value)</span></td>

                @if ($filled)

                <td><span class="block {{ $row->success ? 'text-theme-10' : 'text-theme-24' }}" title="@number($row->difference)">@number($row->value_current)</span></td>

                @endif

                <td><span class="block" title="{{ $row->fee }}">@number($row->fee)</span></td>
                <td><span class="block text-center">{{ $row->type }}</span></td>
                <td><span class="block text-center">{{ $row->status }}</span></td>
                <td><span class="block text-center">{{ ($row->side === 'sell') ? __('order-index.sell') : __('order-index.buy') }}</span></td>
                <td><span class="block text-center">@status($row->filled)</span></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@if (method_exists($list, 'hasPages') && $list->hasPages())
<div class="mt-2">
    {{ $list->appends($REQUEST->input())->links() }}
</div>
@endif