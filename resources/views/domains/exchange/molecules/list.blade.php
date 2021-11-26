<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="exchange-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">#</th>
                <th class="text-left">{{ __('exchange-index.code') }}</th>
                <th class="text-left">{{ __('exchange-index.name') }}</th>
                <th class="text-center">{{ __('exchange-index.created_at') }}</th>
                <th>{{ __('exchange-index.exchange') }}</th>
                <th>{{ __('exchange-index.previous-exchange') }}</th>
                <th>{{ __('exchange-index.difference') }}</th>
                <th class="text-center">{{ __('exchange-index.percent') }}</th>
                <th>{{ __('exchange-index.platform') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $i => $row)

            @php ($link = route('exchange.detail', $row->product_id))

            <tr class="text-right">
                <td class="text-center"><a href="{{ $link }}" class="block">{{ $i + 1 }}</a></td>
                <td class="text-left"><a href="{{ $link }}" class="block">{{ $row->product->acronym }}</a></td>
                <td class="text-left"><a href="{{ $link }}" class="block">{{ $row->product->name }}</a></td>
                <td class="text-center" data-table-sort-value="{{ $row->created_at }}"><a href="{{ $link }}" class="block" title="{{ $row->created_at }}">@datetime($row->created_at)</a></td>
                <td data-table-sort-value="{{ $row->exchange }}"><a href="{{ $link }}" class="block" title="{{ $row->exchange }}">@number($row->exchange)</a></td>
                <td data-table-sort-value="{{ $row->previous_exchange }}"><a href="{{ $link }}" class="block" title="{{ $row->previous_exchange }}">@number($row->previous_exchange)</a></td>
                <td data-table-sort-value="{{ $row->difference }}"><a href="{{ $link }}" class="block">@number($row->difference)</a></td>
                <td class="{{ ($row->percent >= 0) ? 'text-theme-10' : 'text-theme-24' }} font-medium text-center" data-table-sort-value="{{ $row->percent }}"><a href="{{ $link }}" class="block">@number($row->percent, 2)%</a></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank">{{ $row->platform->name }}</a></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>