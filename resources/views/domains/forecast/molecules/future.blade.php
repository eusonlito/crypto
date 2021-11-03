@if ($list->isNotEmpty())

<div id="order-future-table" class="overflow-auto">
    <table class="table table-report sm:mt-2">
        <thead>
            <tr class="text-right">
                <th class="text-center">{{ __('forecast-index.wallet') }}</th>
                <th class="text-center">{{ __('forecast-index.product') }}</th>
                <th class="text-left">{{ __('forecast-index.platform') }}</th>
                <th class="text-center">{{ __('forecast-index.side') }}</th>

                @foreach ($list->first()->keys as $each)
                @if ($each['list']) <th title="{{ $each['description'] }}">{{ $each['title'] }}</th> @endif
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><a href="{{ route('wallet.update', $row->wallet->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->wallet->name }}</a></td>
                <td><a href="{{ route('exchange.detail', $row->product->id) }}" class="block text-center font-semibold whitespace-nowrap">{{ $row->product->acronym }}</a></td>
                <td><a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="block text-left font-semibold whitespace-nowrap">{{ $row->platform->name }}</a></td>
                <td><span class="block text-center">{{ $row->side }}</span></td>

                @foreach ($row->keys as $each)
                    {!! Html::forecastValue($each, $row->values) !!}
                @endforeach
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@endif