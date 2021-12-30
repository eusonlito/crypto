@extends ('layouts.in')

@section ('body')

<div class="overflow-auto md:overflow-admin header-sticky">
    <table id="user-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-left">{{ __('user-index.email') }}</th>
                <th class="text-center">{{ __('user-index.created_at') }}</th>
                <th class="text-center">{{ __('user-index.admin') }}</th>
                <th class="text-center">{{ __('user-index.enabled') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            <tr class="text-right">
                <td><span class="block text-left font-semibold whitespace-nowrap">{{ $row->email }}</span></td>
                <td><span class="block text-center whitespace-nowrap">@datetime($row->created_at)</span></td>
                <td><a href="{{ route('user.update.boolean', [$row->id, 'admin']) }}" data-link-boolean="admin" class="block text-center">@status($row->admin)</a></td>
                <td><a href="{{ route('user.update.boolean', [$row->id, 'enabled']) }}" data-link-boolean="enabled" class="block text-center">@status($row->enabled)</a></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>

@stop
