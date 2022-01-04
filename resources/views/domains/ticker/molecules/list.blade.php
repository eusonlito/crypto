<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="ticker-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr>
                <th>{{ __('ticker-index.name') }}</th>
                <th>{{ __('ticker-index.product') }}</th>
                <th>{{ __('ticker-index.platform') }}</th>
                <th>{{ __('ticker-index.date_at') }}</th>
                <th>{{ __('ticker-index.amount') }}</th>
                <th>{{ __('ticker-index.exchange_reference') }}</th>
                <th>{{ __('ticker-index.value_reference') }}</th>
                <th>{{ __('ticker-index.exchange_current') }}</th>
                <th>{{ __('ticker-index.value_current') }}</th>
                <th>{{ __('ticker-index.exchange_min') }}</th>
                <th>{{ __('ticker-index.value_min') }}</th>
                <th>{{ __('ticker-index.exchange_max') }}</th>
                <th>{{ __('ticker-index.value_max') }}</th>
                <th >{{ __('ticker-index.enabled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            @php ($link = route('ticker.update', $row->id))

            <tr>
                <td><a href="{{ $link }}" class="block font-semibold whitespace-nowrap">{{ $row->product->acronym }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap external">{{ $row->product->acronym }}</a></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-left font-semibold whitespace-nowrap external">{{ $row->platform->name }}</a></td>
                <td><a href="{{ $link }}" class="block">@datetime($row->date_at)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->amount)">@number($row->amount)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->exchange_reference)">@number($row->exchange_reference)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->value_reference)">@number($row->value_reference)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->exchange_current)">@number($row->exchange_current)</a></td>
                <td><a href="{{ $link }}" class="block {{ ($row->value_current >= $row->value_reference) ? 'text-theme-10' : 'text-theme-24' }}" title="@numberString($row->value_current)">@number($row->value_current)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->exchange_min)">@number($row->exchange_min)</a></td>
                <td><a href="{{ $link }}" class="block {{ ($row->value_min >= $row->value_reference) ? 'text-theme-10' : 'text-theme-24' }}" title="@numberString($row->value_min)">@number($row->value_min)</a></td>
                <td><a href="{{ $link }}" class="block" title="@numberString($row->exchange_max)">@number($row->exchange_max)</a></td>
                <td><a href="{{ $link }}" class="block {{ ($row->value_max >= $row->value_reference) ? 'text-theme-10' : 'text-theme-24' }}" title="@numberString($row->value_max)">@number($row->value_max)</a></td>
                <td><a href="{{ route('ticker.update.boolean', [$row->id, 'enabled']) }}" data-link-boolean="enabled" class="block text-center">@status($row->enabled)</a></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
