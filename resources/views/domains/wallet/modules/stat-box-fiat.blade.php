<div class="sm:flex">
    @foreach ($list as $row)

    <div class="box px-5 py-3 flex-1 flex items-center m-4 mb-0">
        <div class="mr-auto">
            <div class="font-medium">
                <a href="{{ route('wallet.update', $row->id) }}">{{ $row->name }}</a>
            </div>

            <div class="text-gray-600 text-xs mt-0.5">{{ $row->platform->name }}</div>
        </div>

        <div class="text-theme-10">@number($row->amount) / @number($row->available)</div>
    </div>

    @endforeach

    @if ($list->count() > 1)

    <div class="box px-5 py-3 flex-1 flex items-center m-4 mb-0">
        <div class="mr-auto">
            <div class="font-medium">
                {{ __('wallet-stat.total') }}
            </div>
        </div>

        <div class="text-theme-10 font-bold">@number($list->sum('amount'))</div>
    </div>

    @endif
</div>
