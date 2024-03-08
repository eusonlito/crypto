@extends ('layouts.in')

@section ('body')

<div class="box flex items-center px-5">
    <div class="nav nav-tabs flex-col sm:flex-row justify-center lg:justify-start mr-auto" role="tablist">
        <a href="{{ route('wallet.update', $row->id) }}" class="py-4 sm:mr-8" role="tab">{{ $row->name }}</a>
        <span class="py-4 sm:mr-8 cursor-default">{{ __('wallet-update.orders') }}</span>
        <a href="{{ route('wallet.update.history', $row->id) }}" class="py-4 sm:mr-8 active" role="tab">{{ __('wallet-update.history') }}</a>
    </div>
</div>

<div class="box p-5">
    @include ('domains.wallet.molecules.chart-history', [
        'history' => $history->reverse()
    ])
</div>

<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="wallet-list-table" class="table table-report sm:mt-2 font-medium text-center" data-table-sort data-table-pagination>
        <thead>
            <tr class="text-right">
                <th>{{ __('wallet-update-history.created_at') }}</th>
                <th>{{ __('wallet-update-history.amount') }}</th>
                <th>{{ __('wallet-update-history.buy_exchange') }}</th>
                <th>{{ __('wallet-update-history.current_exchange') }}</th>
                <th>{{ __('wallet-update-history.buy_value') }}</th>
                <th>{{ __('wallet-update-history.current_value') }}</th>
                <th>{{ __('wallet-update-history.buy_stop') }}</th>
                <th>{{ __('wallet-update-history.sell_stop') }}</th>
                <th>{{ __('wallet-update-history.sell_stoploss') }}</th>
                <th>{{ __('wallet-update-history.custom') }}</th>
                <th>{{ __('wallet-update-history.visible') }}</th>
                <th>{{ __('wallet-update-history.enabled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($history as $row)

            @php ($payload = $row->payload)

            <tr>
                <td data-table-sort-value="{{ $row->created_at }}">@datetime($row->created_at)</td>
                <td data-table-sort-value="{{ $payload->amount }}" data-copy-value="{{ $payload->amount }}" title="@numberString($payload->amount)">@number($payload->amount)</td>
                <td data-table-sort-value="{{ $payload->buy_exchange }}" data-copy-value="{{ $payload->buy_exchange }}" title="@numberString($payload->buy_exchange)">@number($payload->buy_exchange)</td>
                <td data-table-sort-value="{{ $payload->current_exchange }}" data-copy-value="{{ $payload->current_exchange }}" title="@number($payload->current_exchange - $payload->buy_exchange)">@number($payload->current_exchange)</td>
                <td data-table-sort-value="{{ $payload->buy_value }}" data-copy-value="{{ $payload->buy_value }}" title="@numberString($payload->buy_value)">@number($payload->buy_value)</td>
                <td data-table-sort-value="{{ $payload->current_value }}" data-copy-value="{{ $payload->current_value }}" title="@number($payload->current_value - $payload->buy_value)">@number($payload->current_value)</td>
                <td>@status($payload->buy_stop)</td>
                <td>@status($payload->sell_stop)</td>
                <td>@status($payload->sell_stoploss)</td>
                <td>@status($payload->custom)</td>
                <td>@status($payload->visible)</td>
                <td>@status($payload->enabled)</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@stop
