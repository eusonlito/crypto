@if ($list->isEmpty())

<div class="box p-5 text-center">
    {{ __('forecast-index.empty') }}
</div>

@else

<div id="forecast-list-table" class="overflow-auto">
    <table class="table table-report sm:mt-2">
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('forecast-index.date') }}</th>
                <th class="text-left">{{ __('forecast-index.platform') }}</th>
                <th class="text-center">{{ __('forecast-index.product') }}</th>
                <th class="text-center">{{ __('forecast-index.wallet') }}</th>
                <th class="text-center">{{ __('forecast-index.side') }}</th>

                @foreach ($list->first()->keys as $each)
                @if ($each['list']) <th title="{{ $each['description'] }}">{{ $each['title'] }}</th> @endif
                @endforeach

                <th class="text-center">{{ __('forecast-index.selected') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><span class="block text-center" title="{{ $row->created_at }}">@datetime($row->created_at)</span></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-left font-semibold whitespace-nowrap">{{ $row->platform->name }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->product->acronym }}</a></td>
                <td><a href="{{ route('wallet.update', $row->wallet->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->wallet->name }}</a></td>
                <td><span class="block">{{ $row->side }}</span></td>

                @foreach ($row->keys as $each)
                    {!! Html::forecastValue($each, $row->values) !!}
                @endforeach

                <td><span class="block text-center">@status($row->selected)</span></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@if ($list->hasPages())
<div class="mt-2">
    {{ $list->appends($REQUEST->input())->links() }}
</div>
@endif

@endif